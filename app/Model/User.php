<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel
{
    public $useTable = 'users';
    public $displayField = 'username';

    public $validate = array(
        'username' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'Username é obrigatório',
                'allowEmpty' => false
            ),
            'minLength' => array(
                'rule' => array('minLength', 3),
                'message' => 'Username deve ter no mínimo 3 caracteres'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 50),
                'message' => 'Username deve ter no máximo 50 caracteres'
            ),
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'message' => 'Username deve conter apenas letras e números'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Username já existe'
            )
        ),
        'password' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'Senha é obrigatória',
                'allowEmpty' => false,
                'on' => 'create'
            ),
            'minLength' => array(
                'rule' => array('minLength', 6),
                'message' => 'Senha deve ter no mínimo 6 caracteres',
                'allowEmpty' => false,
                'on' => 'create'
            )
        ),
        'role' => array(
            'rule' => array('inList', array('admin', 'author')),
            'message' => 'Role inválida. Deve ser admin ou author',
            'allowEmpty' => false
        ),
    );

    /**
     * Hash da senha antes de salvar
     */
    public function beforeSave($options = array())
    {
        if (!$this->id) {
            // Novo registro
            $this->data[$this->alias]['created'] = date('Y-m-d H:i:s');
        }

        $this->data[$this->alias]['modified'] = date('Y-m-d H:i:s');
        if (!empty($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }

        // Define role padrão como 'author' se não especificado
        if (empty($this->data[$this->alias]['role'])) {
            $this->data[$this->alias]['role'] = 'author';
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

    /**
     * Método para verificar role
     */
    public function isAdmin($userId)
    {
        $user = $this->findById($userId);
        return $user && $user[$this->alias]['role'] === 'admin';
    }

    /**
     * Formata data para padrão brasileiro
     */
    public function afterFind($results, $primary = false)
    {
        foreach ($results as &$result) {
            if (isset($result[$this->alias]['created'])) {
                $result[$this->alias]['created_br'] = date('d/m/Y H:i', strtotime($result[$this->alias]['created']));
            }
            if (isset($result[$this->alias]['modified'])) {
                $result[$this->alias]['modified_br'] = date('d/m/Y H:i', strtotime($result[$this->alias]['modified']));
            }
        }
        return $results;
    }

    /**
     * Busca usuários com filtros
     */
    public function getFilteredUsers($filters = array(), $options = array())
    {
        $conditions = array(
            $this->alias . '.deleted_at' => null  // Exclui usuários deletados
        );

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $conditions['OR'] = array(
                $this->alias . '.username LIKE' => $search,
            );
        }

        if (!empty($filters['role'])) {
            $conditions[$this->alias . '.role'] = $filters['role'];
        }

        $defaults = array(
            'conditions' => $conditions,
            'fields' => array('id', 'username', 'role', 'created', 'modified'),
            'order' => array($this->alias . '.created' => 'DESC')
        );

        $options = array_merge($defaults, $options);

        return $this->find('all', $options);
    }

    /**
     * Soft delete de usuário
     */
    public function softDelete($id)
    {
        $this->id = $id;
        return $this->saveField('deleted_at', date('Y-m-d H:i:s'));
    }

    /**
     * Restaura usuário deletado
     */
    public function restore($id)
    {
        $this->id = $id;
        return $this->saveField('deleted_at', null);
    }
}
