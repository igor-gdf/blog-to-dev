<?php
App::uses('AppController', 'Controller');

/**
 * Users Controller
 * 
 * Gerencia usuários (autenticação, perfil, administração)
 */
class UsersController extends AppController
{
    /**
     * Configura permissões de acesso e comportamento da view
     * Login e registro são públicos
     */
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
            if (empty($this->request->data['User']['role'])) {
                $this->request->data['User']['role'] = 'author';
            }

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
            'conditions' => ['User.deleted_at' => null],
            'fields' => ['id', 'username', 'role', 'created', 'modified'],
            'order' => ['User.created' => 'desc']
        ]);

        $this->set(compact('users'));
    }

    // ======================================================
    // ADMIN: CRIAR USUÁRIO
    // ======================================================
    public function admin_add()
    {
        $this->_checkAdmin();

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
                $this->Flash->success(__('Usuário criado com sucesso.'));
                return $this->redirect(['action' => 'admin_index']);
            }

            $this->Flash->error(__('Erro ao criar usuário. Tente novamente.'));
        }
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

        // Admin não pode editar outro admin
        if ($user['User']['role'] === 'admin' && $user['User']['id'] != $this->Auth->user('id')) {
            $this->Flash->error('Não é permitido editar outro admin.');
            return $this->redirect(['action' => 'admin_index']);
        }

        if ($this->request->is(['post', 'put'])) {
            $this->User->id = $id;
            
            // Se não foi enviada senha, remover do array
            if (empty($this->request->data['User']['password'])) {
                unset($this->request->data['User']['password']);
            }
            
            if ($this->User->save($this->request->data)) {
                $this->Flash->success('Usuário atualizado com sucesso.');
                return $this->redirect(['action' => 'admin_index']);
            }
            $this->Flash->error('Erro ao atualizar usuário.');
        } else {
            $this->request->data = $user;
            unset($this->request->data['User']['password']); // Não enviar senha para o form
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

        // Admin não pode deletar outro admin
        if ($user['User']['role'] === 'admin') {
            $this->Flash->error('Não é permitido excluir outro admin.');
            return $this->redirect(['action' => 'admin_index']);
        }

        // Soft delete
        if ($this->User->softDelete($id)) {
            $this->Flash->success('Usuário excluído com sucesso.');
        } else {
            $this->Flash->error('Erro ao excluir usuário.');
        }

        return $this->redirect(['action' => 'admin_index']);
    }

    // ======================================================
    // USUÁRIO: EDITAR PRÓPRIA CONTA
    // ======================================================
    public function edit()
    {
        $id = $this->Auth->user('id');
        $user = $this->User->findById($id);

        if (!$user) {
            $this->Flash->error('Usuário não encontrado.');
            return $this->redirect(['controller' => 'posts', 'action' => 'index']);
        }

        if ($this->request->is(['post', 'put'])) {
            $this->User->id = $id;
            
            // Se senha foi fornecida, validar confirmação
            if (!empty($this->request->data['User']['password'])) {
                if ($this->request->data['User']['password'] !== $this->request->data['User']['confirm_password']) {
                    $this->Flash->error(__('As senhas não coincidem.'));
                    return;
                }
            } else {
                // Se não foi fornecida senha, remover do array
                unset($this->request->data['User']['password']);
            }
            
            unset($this->request->data['User']['confirm_password']);
            
            // Usuário não pode mudar o próprio role
            unset($this->request->data['User']['role']);
            
            if ($this->User->save($this->request->data)) {
                $this->Flash->success('Conta atualizada com sucesso.');
                return $this->redirect(['action' => 'profile']);
            }
            $this->Flash->error('Erro ao atualizar conta.');
        } else {
            $this->request->data = $user;
            unset($this->request->data['User']['password']);
        }

        $this->set(compact('user'));
    }

    // ======================================================
    // USUÁRIO: EXCLUIR PRÓPRIA CONTA
    // ======================================================
    public function delete()
    {
        $id = $this->Auth->user('id');
        $user = $this->User->findById($id);

        if (!$user) {
            $this->Flash->error('Usuário não encontrado.');
            return $this->redirect(['controller' => 'posts', 'action' => 'index']);
        }

        // Admin não pode se auto-deletar
        if ($user['User']['role'] === 'admin') {
            $this->Flash->error('Administradores não podem excluir a própria conta.');
            return $this->redirect(['action' => 'profile']);
        }

        if ($this->request->is('post')) {
            if ($this->User->softDelete($id)) {
                $this->Flash->success('Sua conta foi excluída com sucesso.');
                return $this->redirect($this->Auth->logout());
            }
            $this->Flash->error('Erro ao excluir conta.');
        }

        $this->set(compact('user'));
    }
}
