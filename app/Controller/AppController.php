<?php
App::uses('Controller', 'Controller');

class AppController extends Controller
{
    public $components = array(
        'Session',
        'Flash',
        'Auth' => array(
            'loginAction' => array('controller' => 'users', 'action' => 'login'),
            'loginRedirect' => array('controller' => 'posts', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => array('className' => 'Blowfish'),
                    'fields' => array(
                        'username' => 'username',
                        'password' => 'password'
                    )
                )
            ),
            'authorize' => array('Controller'),
            'authError' => 'Você não tem permissão para acessar esta área.'
        )
    );

    public function beforeFilter() {
        // Páginas públicas globais
        $this->Auth->allow('index', 'view');
        $this->set('loggedIn', $this->Auth->user());
    }

    public function isAuthorized($user) {
        // Admin tem acesso total
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }
        // Permite o resto por padrão
        return true;
    }
}
