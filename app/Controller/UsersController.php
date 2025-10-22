<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController
{
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login', 'register');
    }

    public function register() {
        $this->set('hideSidebar', true);
        if ($this->request->is('post')) {
            if ($this->request->data['User']['password'] !== $this->request->data['User']['confirm_password']) {
                $this->Flash->error(
                    __('As senhas não coincidem. Por favor, tente novamente.')
                );
                return;
            }

            unset($this->request->data['User']['confirm_password']);
            $this->User->create();

            if ($this->User->save($this->request->data)) {
                $this->Flash->success(
                    __('Usuário registrado com sucesso.')
                );
                return $this->redirect(array('action' => 'login'));
            }

            $this->Flash->error(
                __('Não foi possível registrar o usuário. Por favor, tente novamente.')
            );
        }
    }

    public function login() {
        $this->set('hideSidebar', true);
        if ($this->Auth->user()) {
            // já logado → redireciona
            return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
        }

        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->Flash->success(
                    __('Login realizado com sucesso.')
                );
                $redirectUrl = $this->Auth->redirectUrl();
                return $this->redirect($redirectUrl);
            } else {
                $this->Flash->error(
                    __('Usuário ou senha inválidos.')
                );
            }
        }
    }

    public function profile($id = null) {
        $currentUser = $this->Auth->user();
        if ($id === null) {
            $id = $currentUser['id'];
        }

        $user = $this->User->findById($id);
        if (!$user) {
            throw new NotFoundException(__('Usuário não encontrado.'));
        }
        $this->set('user', $user);
    }

    public function logout() {
        $this->Flash->success(
            __('Você deslogou com sucesso.')
        );
        return $this->redirect($this->Auth->logout());
    }

    // ÁREA ADMIN
    public function admin_index() {}
    public function admin_view() {}
    public function admin_add() {}
    public function admin_edit() {}
    public function admin_delete() {}
}
