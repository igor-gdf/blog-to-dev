<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();

        // Permite login e registro sem autenticação
        $this->Auth->allow(['login', 'register']);

        // Oculta sidebar nas telas públicas
        if (in_array($this->action, ['login', 'register'])) {
            $this->set('hideSidebar', true);
        }
    }

    // ======================================================
    // REGISTRO
    // ======================================================
    public function register()
    {
        if ($this->request->is('post')) {
            $data = $this->request->data;

            // Verifica senhas
            if ($data['User']['password'] !== $data['User']['confirm_password']) {
                $this->Flash->error(__('As senhas não coincidem.'));
                return;
            }

            // Remove campo desnecessário antes de salvar
            unset($data['User']['confirm_password']);

            $this->User->create();

            if ($this->User->save($data)) {
                $this->Flash->success(__('Usuário registrado com sucesso.'));
                return $this->redirect(['action' => 'login']);
            }

            $this->Flash->error(__('Erro ao registrar. Tente novamente.'));
        }
    }

    // ======================================================
    // LOGIN
    // ======================================================
    public function login()
    {
        if ($this->Auth->user()) {
            return $this->redirect(['controller' => 'posts', 'action' => 'index']);
        }

        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->Flash->success(__('Login realizado com sucesso.'));
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Usuário ou senha inválidos.'));
        }
    }

    // ======================================================
    // PERFIL
    // ======================================================
    public function profile($id = null)
    {
        $id = isset($id) && $id ? $id : $this->Auth->user('id');

        $user = $this->User->findById($id);

        if (!$user) {
            throw new NotFoundException(__('Usuário não encontrado.'));
        }

        $this->set(compact('user'));
    }

    // ======================================================
    // LOGOUT
    // ======================================================
    public function logout()
    {
        $this->Flash->success(__('Você deslogou com sucesso.'));
        return $this->redirect($this->Auth->logout());
    }

    // ======================================================
    // ADMIN: LISTAR
    // ======================================================
    public function admin_index()
    {
        $this->_checkAdmin();

        $users = $this->User->find('all', [
            'fields' => ['id', 'username', 'role', 'created', 'modified'],
            'order' => ['User.created' => 'desc']
        ]);

        $this->set(compact('users'));
    }

    // ======================================================
    // ADMIN: EDITAR
    // ======================================================
    public function admin_edit($id = null)
    {
        $this->_checkAdmin();

        if (!$id) {
            $this->Flash->error('Usuário inválido.');
            return $this->redirect(['action' => 'admin_index']);
        }

        $user = $this->User->findById($id);
        if (!$user) {
            $this->Flash->error('Usuário não encontrado.');
            return $this->redirect(['action' => 'admin_index']);
        }

        if ($user['User']['role'] === 'admin') {
            $this->Flash->error('Não é permitido editar outro admin.');
            return $this->redirect(['action' => 'admin_index']);
        }

        if ($this->request->is(['post', 'put'])) {
            $this->User->id = $id;
            if ($this->User->save($this->request->data)) {
                $this->Flash->success('Usuário atualizado com sucesso.');
                return $this->redirect(['action' => 'admin_index']);
            }
            $this->Flash->error('Erro ao atualizar usuário.');
        } else {
            $this->request->data = $user;
        }

        $this->set(compact('user'));
    }

    // ======================================================
    // ADMIN: DELETAR
    // ======================================================
    public function admin_delete($id = null)
    {
        $this->_checkAdmin();

        if (!$id) {
            $this->Flash->error('Usuário inválido.');
            return $this->redirect(['action' => 'admin_index']);
        }

        $user = $this->User->findById($id);
        if (!$user) {
            $this->Flash->error('Usuário não encontrado.');
            return $this->redirect(['action' => 'admin_index']);
        }

        if ($user['User']['role'] === 'admin') {
            $this->Flash->error('Não é permitido excluir outro admin.');
            return $this->redirect(['action' => 'admin_index']);
        }

        if ($this->User->delete($id)) {
            $this->Flash->success('Usuário excluído com sucesso.');
        } else {
            $this->Flash->error('Erro ao excluir usuário.');
        }

        return $this->redirect(['action' => 'admin_index']);
    }

    // ======================================================
    // MÉTODOS PRIVADOS AUXILIARES
    // ======================================================
    private function _checkAdmin()
    {
        $currentUser = $this->Auth->user();
        if (!$currentUser || $currentUser['role'] !== 'admin') {
            $this->Flash->error('Acesso negado.');
            $this->redirect(['controller' => 'posts', 'action' => 'index']);
            return false;
        }
        return true;
    }
}
