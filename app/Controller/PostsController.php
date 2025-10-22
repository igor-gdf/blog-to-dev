<?php

APP::uses('AppController', 'Controller');


class PostsController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('index');
    }

    public function index()
    {
        $conditions = array(
            'Post.status' => 'published',
            'Post.deleted_at' => null
        );

        if (!empty($this->request->query['search'])) {
            $search = trim($this->request->query['search']);

            if ($search !== '') {
                $conditions['OR'] = array(
                    'LOWER(Post.title) LIKE' => '%' . strtolower($search) . '%',
                    'LOWER(Post.content) LIKE' => '%' . strtolower($search) . '%'
                );
            }
        }

        $this->Paginator->settings = array(
            'conditions' => $conditions,
            'limit' => 10,
            'order' => array('Post.created' => 'desc'),
            'paramType' => 'querystring'
        );

        $posts = $this->Paginator->paginate('Post');

        $this->set(compact('posts'));
    }
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Post->create();
            // garante que o post pertence ao usuário logado
            $this->request->data['Post']['user_id'] = $this->Auth->user('id');

            // tenta salvar; validações do model (incluindo content notBlank) serão aplicadas
            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Post criado com sucesso.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('Não foi possível salvar o post. Verifique os campos e tente novamente.'));
            }
        }
    }

    public function view($id = null)
    {
        if (!$id || !$this->Post->exists($id)) {
            throw new NotFoundException(__('Post inválido.'));
        }

        $post = $this->Post->findById($id);
        $this->set('currentUser', $this->Auth->user());
        $this->set('post', $post);
    }
    public function edit($id = null)
    {
        if (!$id || !$this->Post->exists($id)) {
            throw new NotFoundException(__('Post inválido.'));
        }

        $post = $this->Post->findById($id);
        

        // Verifica se o post pertence ao usuário logado
        if ($post['Post']['user_id'] != $this->Auth->user('id')) {
            $this->Flash->error(__('Você não tem permissão para editar este post.'));
            return $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Post->id = $id;
            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Post atualizado com sucesso.'));
                return $this->redirect(array('action' => 'view', $id));
            } else {
                $this->Flash->error(__('Não foi possível atualizar o post. Verifique os campos e tente novamente.'));
            }
        } else {
            $this->request->data = $post;
        }

        $this->set('post', $post);
    }

    public function delete($id = null)
    {
        if (!$id || !$this->Post->exists($id)) {
            throw new NotFoundException(__('Post inválido.'));
        }

        $post = $this->Post->findById($id);

        // Verifica se o post pertence ao usuário logado
        if ($post['Post']['user_id'] != $this->Auth->user('id')) {
            $this->Flash->error(__('Você não tem permissão para deletar este post.'));
            return $this->redirect(array('action' => 'index'));
        }

        if ($this->Post->delete($id)) {
            $this->Flash->success(__('Post deletado com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível deletar o post. Tente novamente.'));
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function dashboard()
    {
        $myPosts = $this->Post->find('all', array(
            'conditions' => array('Post.user_id' => $this->Auth->user('id')),
            'order' => array('Post.created' => 'desc')
        ));
        $totalPosts = $this->Post->find('count');
        $publishedPosts = $this->Post->find('count', array('conditions' => array('Post.status' => 'published')));
        $draftPosts = $this->Post->find('count', array('conditions' => array('Post.status' => 'draft')));

        $this->set(compact('totalPosts', 'publishedPosts', 'draftPosts', 'myPosts'));
    }
}