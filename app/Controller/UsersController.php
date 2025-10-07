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
        // Se já está logado, redireciona
        if ($this->Auth->loggedIn()) {
            return $this->redirect($this->Auth->redirectUrl());
        }
        
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $user = $this->Auth->user();
                $this->Session->setFlash(
                    'Bem-vindo, ' . $user['username'] . '!', 
                    'default', 
                    array('class' => 'alert alert-success')
                );
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Session->setFlash(
                'Usuário ou senha inválidos. Tente novamente.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
        }
    }

    public function logout()
    {
        $this->Session->setFlash(
            'Logout realizado com sucesso!', 
            'default', 
            array('class' => 'alert alert-info')
        );
        return $this->redirect($this->Auth->logout());
    }

    public function index()
    {
        $currentUser = $this->Auth->user();

        // Só admin vê todos os usuários
        if ($currentUser['role'] !== 'admin') {
            $this->Session->setFlash(
                'Acesso negado. Apenas administradores podem visualizar usuários.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
            return $this->redirect('/');
        }

        // Filtros de busca
        $filters = array();
        if ($this->request->is('post') || !empty($this->request->query)) {
            $data = !empty($this->request->data) ? $this->request->data : $this->request->query;
            
            if (!empty($data['search'])) {
                $filters['search'] = trim($data['search']);
            }
            if (!empty($data['role'])) {
                $filters['role'] = $data['role'];
            }
        }

        // Aplicar filtros
        $conditions = array();
        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $conditions['OR'] = array(
                'User.username LIKE' => $search,
                'User.email LIKE' => $search
            );
        }
        if (!empty($filters['role'])) {
            $conditions['User.role'] = $filters['role'];
        }

        // Paginação
        $this->Paginator->settings = array(
            'User' => array(
                'limit' => 15,
                'conditions' => $conditions,
                'order' => array('User.created' => 'DESC'),
                'fields' => array('User.id', 'User.username', 'User.email', 'User.role', 'User.created', 'User.modified')
            )
        );

        $users = $this->Paginator->paginate('User');
        
        $this->set(compact('users', 'filters'));
        $this->set('currentUser', $currentUser);
    }

    public function add()
    {
        $currentUser = $this->Auth->user();
        
        if ($this->request->is('post')) {
            $this->User->create();
            
            // Define role padrão como 'user' se não especificado
            if (empty($this->request->data['User']['role'])) {
                $this->request->data['User']['role'] = 'user';
            }
            
            // Apenas admins podem criar outros admins
            if ($this->request->data['User']['role'] === 'admin' && 
                (!$currentUser || $currentUser['role'] !== 'admin')) {
                $this->request->data['User']['role'] = 'user';
            }
            
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(
                    'Usuário cadastrado com sucesso!', 
                    'default', 
                    array('class' => 'alert alert-success')
                );
                
                // Se usuário não logado, redireciona para login
                if (!$currentUser) {
                    return $this->redirect(array('action' => 'login'));
                }
                
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                'Erro ao cadastrar usuário. Verifique os dados e tente novamente.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
        }
        
        $this->set('currentUser', $currentUser);
    }

    public function edit($id)
    {
        $currentUser = $this->Auth->user();
        
        // Verificações de permissão
        if ($currentUser['role'] !== 'admin' && $currentUser['id'] != $id) {
            $this->Session->setFlash(
                'Acesso negado. Você só pode editar seu próprio perfil.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
            return $this->redirect('/');
        }

        $user = $this->User->findById($id);
        if (!$user) {
            throw new NotFoundException('Usuário não encontrado');
        }

        if ($this->request->is(array('post', 'put'))) {
            // Proteção: Admin não pode perder seus privilégios
            if ($user['User']['role'] === 'admin' && $currentUser['id'] == $id) {
                if (empty($this->request->data['User']['role']) || $this->request->data['User']['role'] !== 'admin') {
                    $this->Session->setFlash(
                        'Erro: Você não pode remover seus próprios privilégios de administrador.', 
                        'default', 
                        array('class' => 'alert alert-danger')
                    );
                    return $this->redirect(array('action' => 'edit', $id));
                }
            }
            
            // Proteção: Usuário comum não pode se tornar admin
            if ($currentUser['role'] !== 'admin' && 
                !empty($this->request->data['User']['role']) && 
                $this->request->data['User']['role'] === 'admin') {
                $this->request->data['User']['role'] = $user['User']['role'];
            }
            
            // Proteção: Admin não pode alterar role de outro admin
            if ($currentUser['role'] === 'admin' && 
                $user['User']['role'] === 'admin' && 
                $currentUser['id'] != $id &&
                !empty($this->request->data['User']['role']) && 
                $this->request->data['User']['role'] !== 'admin') {
                $this->Session->setFlash(
                    'Erro: Você não pode alterar o perfil de outro administrador.', 
                    'default', 
                    array('class' => 'alert alert-danger')
                );
                return $this->redirect(array('action' => 'edit', $id));
            }
            
            // Remove senha se estiver vazia
            if (empty($this->request->data['User']['password'])) {
                unset($this->request->data['User']['password']);
            }

            $this->User->id = $id;
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(
                    'Usuário atualizado com sucesso!', 
                    'default', 
                    array('class' => 'alert alert-success')
                );
                
                if ($currentUser['role'] === 'admin') {
                    return $this->redirect(array('action' => 'index'));
                } else {
                    return $this->redirect('/');
                }
            }
            $this->Session->setFlash(
                'Erro ao atualizar usuário. Verifique os dados e tente novamente.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
        }

        if (!$this->request->data) {
            unset($user['User']['password']);
            $this->request->data = $user;
        }
        
        $this->set(compact('user'));
        $this->set('currentUser', $currentUser);
    }

    public function delete($id)
    {
        $currentUser = $this->Auth->user();
        
        // Apenas admins podem deletar usuários
        if ($currentUser['role'] !== 'admin') {
            $this->Session->setFlash(
                'Acesso negado. Apenas administradores podem deletar usuários.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
            return $this->redirect('/');
        }
        
        $user = $this->User->findById($id);
        if (!$user) {
            $this->Session->setFlash(
                'Usuário não encontrado.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
            return $this->redirect(array('action' => 'index'));
        }
        
        // Proteção: Admin não pode deletar a si mesmo
        if ($currentUser['id'] == $id) {
            $this->Session->setFlash(
                'Erro: Você não pode deletar sua própria conta.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
            return $this->redirect(array('action' => 'index'));
        }
        
        // Proteção: Admin não pode deletar outro admin
        if ($user['User']['role'] === 'admin') {
            $this->Session->setFlash(
                'Erro: Você não pode deletar outro administrador.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
            return $this->redirect(array('action' => 'index'));
        }

        $this->User->id = $id;
        if ($this->User->delete()) {
            $this->Session->setFlash(
                'Usuário deletado com sucesso!', 
                'default', 
                array('class' => 'alert alert-success')
            );
        } else {
            $this->Session->setFlash(
                'Erro ao deletar usuário.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
        }
        
        return $this->redirect(array('action' => 'index'));
    }
}
