<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout', 'register');
    }
    public function register(){
        if ($this->request->is('post')) {
            if ($this->request->data['User']['password'] !== $this->request->data['User']['confirm_password']) {
                $this->Session->setFlash(__d('cake_dev', 'As senhas não coincidem. Por favor, tente novamente.'), 'default', array('class' => 'alert alert-danger position-fixed bottom-0 end-0 m-3', 'id' => 'FlashMessage'));
                return;
            }
            unset($this->request->data['User']['password_confirm']);

            $this->User->create();

            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__d('cake_dev', 'Usuário registrado com sucesso.'), 'default', array('class' => 'alert alert-success position-fixed bottom-0 end-0 m-3'));
                return $this->redirect(array('action' => 'login'));
            }
            $this->Session->setFlash(__d('cake_dev', 'Não foi possível registrar o usuário. Por favor, tente novamente.'), 'default', array('class' => 'alert alert-danger position-fixed bottom-0 end-0 m-3', 'id' => 'FlashMessage'));
        }
    }
    public function login(){
        // Forçar logout se já estiver logado
        if ($this->Auth->loggedIn()) {
            $this->Auth->logout();
        }
        
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->Session->setFlash(__('Login realizado com sucesso.'), 'default', array('class' => 'alert alert-success position-fixed bottom-0 end-0 m-3', 'id' => 'FlashMessage'));

                // tenta redirecionar para a URL original armazenada pelo Auth
                $redirectUrl = $this->Auth->redirectUrl();
                // se o redirect apontar para a própria página de login ou estiver vazio, usar posts/index
                if (empty($redirectUrl) || strpos($redirectUrl, '/users/login') !== false) {
                    $redirectUrl = array('controller' => 'posts', 'action' => 'index');
                }

                return $this->redirect($redirectUrl);
            } else {
                $this->Session->setFlash(__('Usuário ou senha inválidos.'), 'default', array('class' => 'alert alert-danger position-fixed bottom-0 end-0 m-3', 'id' => 'FlashMessage'));
            }
        }
    }
    public function editProfile( ) {

    }
    public function profile($id = null){
        $currentUser = $this->Auth->user();

        if ($id === null) {
            $id = $currentUser['id'];
        }

        $user = $this->User->findById($id);
        if (!$user) {
            throw new NotFoundException(__('Usuário não encontrado'));
        }
        $this->set('user', $user);
    }
    public function delete(){

    }
    public function logout(){
        if (!$this->Auth->loggedIn()) {
            $this->Session->setFlash(__('Você precisa estar logado para sair.'), 'default', array('class' => 'alert alert-warning position-fixed bottom-0 end-0 m-3', 'id' => 'FlashMessage'));
            return $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }

    $this->Session->setFlash(__d('cake_dev', 'Você deslogou com sucesso.'), 'default', array('class' => 'alert alert-success position-fixed bottom-0 end-0 m-3', 'id' => 'FlashMessage'));
        return $this->redirect($this->Auth->logout());
    }

    //Funções administrativas
    public function admin_index(){}
    //Listar todos os usuários
    public function admin_view(){}
    //Ver detalhes de um usuário específico
    public function admin_add(){}
    //Adicionar um novo usuário
    public function admin_edit(){}
    //Editar um usuário existente
    public function admin_delete(){}
    //Excluir um usuário

}