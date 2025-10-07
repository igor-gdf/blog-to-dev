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
                'message' => 'Username é obrigatório'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Username já existe'
            )
        ),
        'password' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'Senha é obrigatória'
            ),
            'minLength' => array(
                'rule' => array('minLength', 6),
                'message' => 'Senha deve ter no mínimo 6 caracteres'
            )
        ),
        'role' => array(
            'rule' => array('inList', array('admin', 'author')),
            'message' => 'Role inválida'
        )
    );

    /**
     * Hash da senha antes de salvar
     */
    public function beforeSave($options = array())
    {
        if (!empty($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
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
}
