<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController
{
    public $uses = array('User');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout', 'add');
    }

    public function login()
    {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Session->setFlash('Usuário ou senha inválidos');
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    public function index()
    {
        $currentUser = $this->Auth->user();

        // Só admin vê todos os usuários
        if ($currentUser['role'] !== 'admin') {
            $this->Session->setFlash('Acesso negado');
            return $this->redirect('/');
        }

        $users = $this->User->find('all', array(
            'order' => array('User.created' => 'DESC')
        ));
        $this->set(compact('users'));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('Usuário cadastrado com sucesso!');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash('Erro ao cadastrar usuário.');
        }
    }

    public function edit($id)
    {
        $currentUser = $this->Auth->user();
        if ($currentUser['role'] !== 'admin' && $currentUser['id'] != $id) {
            $this->Session->setFlash('Acesso negado');
            return $this->redirect('/');
        }

        $user = $this->User->findById($id);
        if (!$user) {
            throw new NotFoundException('Usuário não encontrado');
        }

        if ($this->request->is(array('post', 'put'))) {
            if (empty($this->request->data['User']['password'])) {
                unset($this->request->data['User']['password']);
            }

            $this->User->id = $id;
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('Usuário atualizado com sucesso!');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash('Erro ao atualizar usuário.');
        }

        if (!$this->request->data) {
            unset($user['User']['password']);
            $this->request->data = $user;
        }
    }

    public function delete($id)
    {
        $currentUser = $this->Auth->user();
        if ($currentUser['role'] !== 'admin') {
            $this->Session->setFlash('Acesso negado');
            return $this->redirect('/');
        }

        $this->User->id = $id;
        if ($this->User->delete()) {
            $this->Session->setFlash('Usuário deletado com sucesso!');
        } else {
            $this->Session->setFlash('Erro ao deletar usuário.');
        }
        return $this->redirect(array('action' => 'index'));
    }
}
