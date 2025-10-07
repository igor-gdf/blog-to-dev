<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    
    public $components = array(
        'Session',
        'Paginator',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'login'
            ),
            'loginRedirect' => array(
                'controller' => 'posts',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'posts',
                'action' => 'public_index'
            ),
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'User',
                    'fields' => array('username' => 'username', 'password' => 'password'),
                    'passwordHasher' => 'Blowfish'
                )
            ),
            'authorize' => array('Controller'),
            'authError' => 'Você precisa estar logado para acessar esta página.',
            'flash' => array(
                'element' => 'default',
                'key' => 'auth',
                'params' => array('class' => 'alert alert-warning')
            )
        )
    );
    
    public $helpers = array(
        'Html',
        'Form',
        'Session',
        'Paginator',
        'Time',
        'Js' => array('Jquery')
    );

    /**
     * Executado antes de cada action
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        
        // Configurações globais
        $this->set('loggedInUser', $this->Auth->user());
        
        // Permite acesso à página inicial para todos
        if ($this->request->controller === 'pages') {
            $this->Auth->allow();
        }
    }

    /**
     * Autorização básica - pode ser sobrescrita nos controllers
     */
    public function isAuthorized($user)
    {
        // Por padrão, usuários logados têm acesso
        return true;
    }

    /**
     * Método para exibir mensagens flash personalizadas
     */
    protected function setFlashMessage($message, $type = 'success')
    {
        $class = 'alert alert-' . $type;
        $this->Session->setFlash($message, 'default', array('class' => $class));
    }

    /**
     * Verifica se o usuário atual é admin
     */
    protected function isAdmin()
    {
        $user = $this->Auth->user();
        return $user && $user['role'] === 'admin';
    }

    /**
     * Redireciona não-admins com mensagem de erro
     */
    protected function requireAdmin()
    {
        if (!$this->isAdmin()) {
            $this->setFlashMessage('Acesso negado. Apenas administradores podem acessar esta página.', 'danger');
            $this->redirect('/');
            return false;
        }
        return true;
    }
}
