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
        // formata título se enviado
        if (!empty($this->data[$this->alias]['title'])) {
            $this->data[$this->alias]['title'] = ucwords(strtolower($this->data[$this->alias]['title']));
        }

        // valor padrão para status
        if (empty($this->data[$this->alias]['status'])) {
            $this->data[$this->alias]['status'] = 'draft';
        }

        // Remove literais indesejadas que causam erro no Postgres
        foreach (array('created', 'modified') as $tsField) {
            if (isset($this->data[$this->alias][$tsField])) {
                $val = $this->data[$this->alias][$tsField];
                if ($val === 'CURRENT_TIMESTAMP' || $val === 'current_timestamp' || $val === '') {
                    unset($this->data[$this->alias][$tsField]);
                }
            }
        }

        // Define timestamps em PHP para evitar problemas com expressões SQL em inserts
        $now = date('Y-m-d H:i:s');

        // se for insert e created não foi enviado, define created
        if (empty($this->data[$this->alias]['id']) && empty($this->data[$this->alias]['created'])) {
            $this->data[$this->alias]['created'] = $now;
        }

        // sempre atualiza modified
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
}
