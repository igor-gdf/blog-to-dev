<?php
App::uses('AppController', 'Controller');

/**
 * Posts Controller
 * 
 * Gerencia posts do blog (CRUD, publicação, rascunhos)
 */
class PostsController extends AppController
{
    /**
     * Configura permissões de acesso
     * index e view são públicos, demais actions requerem autenticação
     */
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
                $status = $this->request->data['Post']['status'];
                if ($status === 'draft') {
                    $this->Flash->success(__('Rascunho salvo com sucesso.'));
                } else {
                    $this->Flash->success(__('Post publicado com sucesso.'));
                }
                return $this->redirect(['action' => 'dashboard']);
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
                $status = $this->request->data['Post']['status'];
                if ($status === 'draft') {
                    $this->Flash->success(__('Rascunho atualizado com sucesso.'));
                } else {
                    $this->Flash->success(__('Post publicado com sucesso.'));
                }
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

        // Soft delete
        $this->Post->id = $id;
        if ($this->Post->saveField('deleted_at', date('Y-m-d H:i:s'))) {
            $this->Flash->success(__('Post deletado com sucesso.'));
        } else {
            $this->Flash->error(__('Erro ao deletar post.'));
        }

        return $this->redirect(['action' => 'dashboard']);
    }

    // ======================================================
    // DASHBOARD DO USUÁRIO
    // ======================================================

    public function dashboard()
    {
        $userId = $this->Auth->user('id');
        $conditions = [
            'Post.user_id' => $userId,
            'Post.deleted_at' => null
        ];

        if (!empty($this->request->query['status'])) {
            $conditions['Post.status'] = $this->request->query['status'];
        }

        $myPosts = $this->Post->find('all', [
            'conditions' => $conditions,
            'order' => ['Post.created' => 'desc']
        ]);

        $totalPosts = $this->Post->find('count', [
            'conditions' => ['Post.user_id' => $userId, 'Post.deleted_at' => null]
        ]);
        $publishedPosts = $this->Post->find('count', [
            'conditions' => [
                'Post.status' => 'published',
                'Post.user_id' => $userId,
                'Post.deleted_at' => null
            ]
        ]);
        $draftPosts = $this->Post->find('count', [
            'conditions' => [
                'Post.status' => 'draft',
                'Post.user_id' => $userId,
                'Post.deleted_at' => null
            ]
        ]);

        $this->set(compact('totalPosts', 'publishedPosts', 'draftPosts', 'myPosts'));
    }

    // ======================================================
    // ADMIN: LISTAR TODOS OS POSTS
    // ======================================================

    public function admin_index()
    {
        $this->_checkAdmin();
        $conditions = ['Post.deleted_at' => null];
        if (!empty($this->request->query['status'])) {
            $conditions['Post.status'] = $this->request->query['status'];
        }

        $posts = $this->Post->find('all', [
            'conditions' => $conditions,
            'order' => ['Post.created' => 'desc']
        ]);

        $allPosts = $this->Post->find('all', [
            'conditions' => ['Post.deleted_at' => null],
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
     * Garante que o post pertence ao usuário atual ou que é admin.
     */
    private function _authorizeOwner($post)
    {
        if ($this->_isOwnerOrAdmin($post['Post']['user_id'])) {
            return true;
        }
        
        $this->Flash->error(__('Você não tem permissão para realizar esta ação.'));
        $this->redirect(['action' => 'index']);
        return false;
    }
}


