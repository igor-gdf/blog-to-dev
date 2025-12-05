<?php
App::uses('Controller', 'Controller');

class AppController extends Controller
{
    public $components = array(
        'Paginator',
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

    /**
     * Verifica se usuário atual é admin
     * Redireciona para posts/index se não for
     */
    protected function _checkAdmin()
    {
        $currentUser = $this->Auth->user();
        if (!$currentUser || $currentUser['role'] !== 'admin') {
            $this->Flash->error('Acesso restrito a administradores.');
            $this->redirect(['controller' => 'posts', 'action' => 'index']);
            return false;
        }
        return true;
    }

    /**
     * Verifica se usuário atual é admin (retorna boolean)
     */
    protected function _isAdmin()
    {
        $currentUser = $this->Auth->user();
        return $currentUser && isset($currentUser['role']) && $currentUser['role'] === 'admin';
    }

    /**
     * Verifica se usuário atual é dono do recurso ou admin
     */
    protected function _isOwnerOrAdmin($ownerId)
    {
        $currentUser = $this->Auth->user();
        if (!$currentUser) {
            return false;
        }
        
        return $this->_isAdmin() || $currentUser['id'] == $ownerId;
    }
}
