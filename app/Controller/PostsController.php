<?php
App::uses('AppController', 'Controller');

class PostsController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();

        // Permite acesso público apenas ao índice e visualização
        $this->Auth->allow(['index', 'view']);
    }

    // ======================================================
    // LISTAGEM PÚBLICA COM FILTROS
    // ======================================================
    public function index()
    {
        $conditions = [
            'Post.status' => 'published',
            'Post.deleted_at' => null
        ];

        // Filtro por texto
        $search = isset($this->request->query['search']) ? trim(strtolower($this->request->query['search'])) : '';
        if ($search !== '') {
            $conditions['OR'] = [
                'LOWER(Post.title) LIKE' => "%{$search}%",
                'LOWER(Post.content) LIKE' => "%{$search}%",
                'LOWER(User.username) LIKE' => "%{$search}%"
            ];
        }


        // Filtro por datas
        if (!empty($this->request->query('created_from'))) {
            $conditions['Post.created >='] = $this->request->query('created_from') . ' 00:00:00';
        }
        if (!empty($this->request->query('created_to'))) {
            $conditions['Post.created <='] = $this->request->query('created_to') . ' 23:59:59';
        }

        // Paginação
        $this->Paginator->settings = [
            'conditions' => $conditions,
            'limit' => 10,
            'order' => ['Post.created' => 'desc'],
            'paramType' => 'querystring'
        ];

        $posts = $this->Paginator->paginate('Post');
        $this->set(compact('posts'));
    }

    // ======================================================
    // ADICIONAR POST
    // ======================================================
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Post->create();
            $this->request->data['Post']['user_id'] = $this->Auth->user('id');

            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Post criado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Não foi possível salvar o post. Verifique os campos e tente novamente.'));
        }
    }

    // ======================================================
    // VISUALIZAR POST
    // ======================================================
    public function view($id = null)
    {
        $post = $this->_getPostOrThrow($id);
        $this->set([
            'currentUser' => $this->Auth->user(),
            'post' => $post
        ]);
    }

    // ======================================================
    // EDITAR POST
    // ======================================================
    public function edit($id = null)
    {
        $post = $this->_getPostOrThrow($id);

        $this->_authorizeOwner($post);

        if ($this->request->is(['post', 'put'])) {
            $this->Post->id = $id;
            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Post atualizado com sucesso.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            $this->Flash->error(__('Erro ao atualizar o post. Verifique os campos.'));
        } else {
            $this->request->data = $post;
        }

        $this->set(compact('post'));
    }

    // ======================================================
    // DELETAR POST
    // ======================================================
    public function delete($id = null)
    {
        $this->request->allowMethod('post', 'delete');

        $post = $this->_getPostOrThrow($id);
        $this->_authorizeOwner($post);

        if ($this->Post->delete($id)) {
            $this->Flash->success(__('Post deletado com sucesso.'));
        } else {
            $this->Flash->error(__('Erro ao deletar post.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    // ======================================================
    // DASHBOARD DO USUÁRIO
    // ======================================================

    public function dashboard()
    {
        $userId = $this->Auth->user('id');
        $conditions = ['Post.user_id' => $userId];

        if (!empty($this->request->query['status'])) {
            $conditions['Post.status'] = $this->request->query['status'];
        }

        $myPosts = $this->Post->find('all', [
            'conditions' => $conditions,
            'order' => ['Post.created' => 'desc']
        ]);

        $totalPosts = $this->Post->find('count', ['conditions' => ['Post.user_id' => $userId]]);
        $publishedPosts = $this->Post->find('count', [
            'conditions' => ['Post.status' => 'published', 'Post.user_id' => $userId]
        ]);
        $draftPosts = $this->Post->find('count', [
            'conditions' => ['Post.status' => 'draft', 'Post.user_id' => $userId]
        ]);

        $this->set(compact('totalPosts', 'publishedPosts', 'draftPosts', 'myPosts'));
    }

    // ======================================================
    // ADMIN: LISTAR TODOS OS POSTS
    // ======================================================

    public function admin_index()
    {
        $this->_checkAdmin();
        $conditions = [];
        if (!empty($this->request->query['status'])) {
            $conditions['Post.status'] = $this->request->query['status'];
        }

        $posts = $this->Post->find('all', [
            'conditions' => $conditions,
            'order' => ['Post.created' => 'desc']
        ]);

        $allPosts = $this->Post->find('all', [
            'conditions' => $conditions,
            'order' => ['Post.created' => 'desc']
        ]);

        $this->set(compact('posts', 'allPosts'));
    }

    // ======================================================
    // MÉTODOS PRIVADOS AUXILIARES
    // ======================================================

    /**
     * Busca post ou lança exceção 404.
     */
    private function _getPostOrThrow($id)
    {
        if (!$id || !$this->Post->exists($id)) {
            throw new NotFoundException(__('Post inválido.'));
        }

        return $this->Post->findById($id);
    }

    /**
     * Garante que o post pertence ao usuário atual.
     */
    private function _authorizeOwner($post)
    {
        if ($post['Post']['user_id'] != $this->Auth->user('id')) {
            $this->Flash->error(__('Você não tem permissão para realizar esta ação.'));
            $this->redirect(['action' => 'index']);
            return false;
        }
        return true;
    }
    private function _checkAdmin()
    {
        $user = $this->Auth->user();
        if (!$user || $user['role'] !== 'admin') {
            $this->Flash->error('Acesso restrito a administradores.');
            $this->redirect(['controller' => 'posts', 'action' => 'index']);
            exit;
        }
    }


}
