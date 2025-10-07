<?php
App::uses('AppModel', 'Model');

class Post extends AppModel
{
    public $useTable = 'posts';
    public $displayField = 'title';

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    public $validate = array(
        'title' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'Título é obrigatório'
            ),
            'minLength' => array(
                'rule' => array('minLength', 3),
                'message' => 'Título deve ter no mínimo 3 caracteres'
            )
        ),
        'content' => array(
            'rule' => 'notBlank',
            'message' => 'Conteúdo é obrigatório'
        ),
        'status' => array(
            'rule' => array('inList', array('draft', 'published', 'deleted')),
            'message' => 'Status inválido'
        )
    );

    /**
     * Hooks
     */
    public function beforeSave($options = array())
    {
        // Padroniza título
        if (isset($this->data[$this->alias]['title'])) {
            $this->data[$this->alias]['title'] = ucwords(strtolower($this->data[$this->alias]['title']));
        }

        // Status padrão
        if (empty($this->data[$this->alias]['status'])) {
            $this->data[$this->alias]['status'] = 'draft';
        }

        // Configurar timestamps manualmente para PostgreSQL
        $now = date('Y-m-d H:i:s');
        if (empty($this->id)) {
            // Novo registro
            $this->data[$this->alias]['created'] = $now;
        }
        $this->data[$this->alias]['modified'] = $now;

        return true;
    }

    public function afterFind($results, $primary = false)
    {
        foreach ($results as &$result) {
            if (isset($result[$this->alias]['created'])) {
                $result[$this->alias]['created'] = date('d/m/Y H:i', strtotime($result[$this->alias]['created']));
            }
            if (isset($result[$this->alias]['deleted_at']) && $result[$this->alias]['deleted_at']) {
                $result[$this->alias]['status'] = 'deleted';
            }
        }
        return $results;
    }

    /**
     * Métodos personalizados
     */

    public function getPublished($limit = 10)
    {
        return $this->find('all', array(
            'conditions' => array(
                $this->alias . '.status' => 'published',
                $this->alias . '.deleted_at' => null
            ),
            'order' => array($this->alias . '.created' => 'DESC'),
            'limit' => $limit
        ));
    }

    public function getByUser($userId, $status = null)
    {
        $conditions = array(
            $this->alias . '.user_id' => $userId,
            $this->alias . '.deleted_at' => null
        );

        if ($status) {
            $conditions[$this->alias . '.status'] = $status;
        }

        return $this->find('all', array(
            'conditions' => $conditions,
            'order' => array($this->alias . '.created' => 'DESC')
        ));
    }

    public function publish($id, $currentUser)
    {
        $post = $this->findById($id);
        if (!$post || $post[$this->alias]['deleted_at']) return false;

        if ($currentUser['role'] !== 'admin' && $post[$this->alias]['user_id'] != $currentUser['id']) {
            return false;
        }

        $this->id = $id;
        return $this->saveField('status', 'published');
    }

    public function deletePost($id, $currentUser)
    {
        $post = $this->findById($id);
        if (!$post || $post[$this->alias]['deleted_at']) return false;

        if ($currentUser['role'] !== 'admin' && $post[$this->alias]['user_id'] != $currentUser['id']) {
            return false;
        }

        $this->id = $id;
        return $this->saveField('deleted_at', date('Y-m-d H:i:s'));
    }

    public function editPost($id, $data, $currentUser)
    {
        $post = $this->findById($id);
        if (!$post || $post[$this->alias]['deleted_at']) return false;

        if ($currentUser['role'] !== 'admin' && $post[$this->alias]['user_id'] != $currentUser['id']) {
            return false;
        }

        $this->id = $id;
        $this->set($data);
        return $this->save();
    }

    /**
     * Aplica filtros na busca de posts
     * @param array $filters Filtros a serem aplicados
     * @param array $user Usuário logado (para verificar permissões)
     * @return array Condições para o find
     */
    public function applyFilters($filters = array(), $user = null)
    {
        $conditions = array();
        
        // Sempre excluir posts deletados fisicamente
        $conditions[$this->alias . '.deleted_at'] = null;
        
        // Filtro de busca (título e conteúdo)
        if (!empty($filters['search'])) {
            $search = '%' . strtolower(trim($filters['search'])) . '%';
            $conditions['OR'] = array(
                'LOWER(' . $this->alias . '.title) LIKE' => $search,
                'LOWER(' . $this->alias . '.content) LIKE' => $search
            );
        }
        
        // Filtro de status
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $conditions[$this->alias . '.status'] = 'published';
            } elseif ($filters['status'] === 'inactive') {
                $conditions[$this->alias . '.status'] = array('draft', 'deleted');
            } else {
                $conditions[$this->alias . '.status'] = $filters['status'];
            }
        }
        
        // Filtro de data inicial
        if (!empty($filters['date_start'])) {
            $conditions[$this->alias . '.created >='] = $filters['date_start'] . ' 00:00:00';
        }
        
        // Filtro de data final
        if (!empty($filters['date_end'])) {
            $conditions[$this->alias . '.created <='] = $filters['date_end'] . ' 23:59:59';
        }
        
        // Filtro de autor (se especificado)
        if (!empty($filters['author_id'])) {
            $conditions[$this->alias . '.user_id'] = $filters['author_id'];
        }
        
        // Controle de acesso baseado no usuário
        if ($user) {
            if ($user['role'] !== 'admin') {
                // Usuários comuns só veem seus próprios posts
                $conditions[$this->alias . '.user_id'] = $user['id'];
            }
        } else {
            // Visitantes só veem posts publicados
            $conditions[$this->alias . '.status'] = 'published';
        }
        
        return $conditions;
    }

    /**
     * Busca posts com filtros aplicados
     * @param array $filters Filtros
     * @param array $user Usuário logado
     * @param array $options Opções adicionais (limit, order, etc)
     * @return array
     */
    public function getFilteredPosts($filters = array(), $user = null, $options = array())
    {
        $conditions = $this->applyFilters($filters, $user);
        
        $defaults = array(
            'conditions' => $conditions,
            'contain' => array('User'),
            'order' => array($this->alias . '.created' => 'DESC')
        );
        
        $options = array_merge($defaults, $options);
        
        return $this->find('all', $options);
    }

    /**
     * Conta posts com filtros aplicados
     * @param array $filters Filtros
     * @param array $user Usuário logado
     * @return int
     */
    public function countFilteredPosts($filters = array(), $user = null)
    {
        $conditions = $this->applyFilters($filters, $user);
        
        return $this->find('count', array(
            'conditions' => $conditions
        ));
    }

    /**
     * Formata data para padrão brasileiro
     * @param string $date Data no formato Y-m-d H:i:s
     * @return string Data formatada
     */
    public function formatDateBR($date)
    {
        if (empty($date)) return '';
        
        $timestamp = strtotime($date);
        return date('d/m/Y H:i', $timestamp);
    }
}
