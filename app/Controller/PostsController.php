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
        // Limpar filtros
        if (isset($this->request->query['clear']) && $this->request->query['clear'] == 1) {
            $this->Session->delete('Post.filters');
            return $this->redirect(['action' => 'index']);
        }

        // Processar filtros apenas via POST
        if ($this->request->is('post')) {
            $filters = [
                'search' => isset($this->request->data['search']) ? trim(strtolower($this->request->data['search'])) : '',
                'created_from' => isset($this->request->data['created_from']) ? $this->_formatDate($this->request->data['created_from']) : '',
                'created_to' => isset($this->request->data['created_to']) ? $this->_formatDate($this->request->data['created_to']) : ''
            ];
            $this->Session->write('Post.filters', $filters);
            return $this->redirect(['action' => 'index']);
        }

        // Recuperar filtros da sessão
        $filters = $this->Session->read('Post.filters');
        if (!is_array($filters)) {
            $filters = ['search' => '', 'created_from' => '', 'created_to' => ''];
        }

        $conditions = [
            'Post.status' => 'published',
            'Post.deleted_at' => null
        ];

        // Aplicar filtro por texto
        if (!empty($filters['search'])) {
            $conditions['OR'] = [
                'LOWER(Post.title) LIKE' => "%{$filters['search']}%",
                'LOWER(Post.content) LIKE' => "%{$filters['search']}%",
                'LOWER(User.username) LIKE' => "%{$filters['search']}%"
            ];
        }

        // Aplicar filtro por datas
        if (!empty($filters['created_from'])) {
            $conditions['Post.created >='] = $filters['created_from'] . ' 00:00:00';
        }
        if (!empty($filters['created_to'])) {
            $conditions['Post.created <='] = $filters['created_to'] . ' 23:59:59';
        }

        // Paginação
        $this->Paginator->settings = [
            'conditions' => $conditions,
            'limit' => 10,
            'order' => ['Post.created' => 'desc']
        ];

        $posts = $this->Paginator->paginate('Post');
        $this->set(compact('posts', 'filters'));
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
        $filterStatus = $this->Session->read('Post.dashboard.status');

        // Processar filtro apenas via POST
        if ($this->request->is('post')) {
            $filterStatus = isset($this->request->data['status']) ? $this->request->data['status'] : '';
            $this->Session->write('Post.dashboard.status', $filterStatus);
            return $this->redirect(['action' => 'dashboard']);
        }

        // Limpar filtro
        if (isset($this->request->query['clear']) && $this->request->query['clear'] == 1) {
            $this->Session->delete('Post.dashboard.status');
            return $this->redirect(['action' => 'dashboard']);
        }

        $conditions = [
            'Post.user_id' => $userId,
            'Post.deleted_at' => null
        ];

        if (!empty($filterStatus)) {
            $conditions['Post.status'] = $filterStatus;
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
        $filterStatus = $this->Session->read('Post.admin.status');

        // Processar filtro apenas via POST
        if ($this->request->is('post')) {
            $filterStatus = isset($this->request->data['status']) ? $this->request->data['status'] : '';
            $this->Session->write('Post.admin.status', $filterStatus);
            return $this->redirect(['action' => 'admin_index']);
        }

        // Limpar filtro
        if (isset($this->request->query['clear']) && $this->request->query['clear'] == 1) {
            $this->Session->delete('Post.admin.status');
            return $this->redirect(['action' => 'admin_index']);
        }

        $conditions = ['Post.deleted_at' => null];
        if (!empty($filterStatus)) {
            $conditions['Post.status'] = $filterStatus;
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

    /**
     * Formata data para o padrão YYYY-MM-DD
     * Aceita formatos: DD/MM/YYYY, DD-MM-YYYY, YYYY-MM-DD
     */
    private function _formatDate($date)
    {
        if (empty($date)) {
            return '';
        }

        // Se já está no formato YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }

        // Tenta converter de DD/MM/YYYY ou DD-MM-YYYY
        if (preg_match('/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/', $date, $matches)) {
            return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
        }

        return '';
    }
}


