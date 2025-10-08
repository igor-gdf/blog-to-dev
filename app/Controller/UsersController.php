<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        // Permitir que os usuários se registrem e façam logout.
        $this->Auth->allow('login', 'logout', 'register');
    }
    public function register(){
        if ($this->request->is('post')) {
            if ($this->request->data['User']['password'] !== $this->request->data['User']['confirm_password']) {
                $this->Session->setFlash(__d('cake_dev', 'As senhas não coincidem. Por favor, tente novamente.'), 'default', array('class' => 'alert alert-danger'));
                return;
            }
            unset($this->request->data['User']['password_confirm']);

            $this->User->create();

            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__d('cake_dev', 'Usuário registrado com sucesso.'), 'default', array('class' => 'alert alert-success'));
                return $this->redirect(array('action' => 'login'));
            }
            $this->Session->setFlash(__d('cake_dev', 'Não foi possível registrar o usuário. Por favor, tente novamente.'), 'default', array('class' => 'alert alert-danger'));
        }
    }
    public function login(){
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->Session->setFlash(__('Você logou com sucesso.'), 'default', array('class' => 'alert alert-success'));
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Session->setFlash(__('Usuário ou senha inválidos, tente novamente'), 'default', array('class' => 'alert alert-danger'));
        }
    }
    public function editProfile( ) {

    }
    public function profile(){

    }
    public function delete(){

    }
    public function logout(){
        $this->Session->setFlash(__d('cake_dev', 'Você deslogou com sucesso.'), 'default', array('class' => 'alert alert-success'));
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