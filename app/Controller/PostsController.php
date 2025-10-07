<?php
App::uses('AppController', 'Controller');

class PostsController extends AppController
{
    public $uses = array('Post');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('view');
    }

    // Lista posts
    public function index()
    {
        $user = $this->Auth->user();

        if ($user['role'] === 'admin') {
            $posts = $this->Post->find('all', array(
                'conditions' => array('Post.deleted_at' => null),
                'order' => array('Post.created' => 'DESC')
            ));
        } else {
            
            $posts = $this->Post->getByUser($user['id']);
        }
        
        $this->set('auth_user', $user); 
        $this->set(compact('posts'));
    }

    // Visualizar um post
    public function view($id)
    {
        $post = $this->Post->findById($id);
        if (!$post || ($post['Post']['deleted_at'] && $this->Auth->user('role') !== 'admin')) {
            throw new NotFoundException('Post não encontrado');
        }
        $this->set('post', $post);
    }

    // Adicionar post
    public function add()
    {
        if ($this->request->is('post')) {
            $this->request->data['Post']['user_id'] = $this->Auth->user('id');
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash('Post criado com sucesso!');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash('Erro ao criar post.');
        }
    }

    // Editar post
    public function edit($id)
    {
        $user = $this->Auth->user();
        $post = $this->Post->findById($id);

        if (!$post) throw new NotFoundException('Post não encontrado');

        if ($this->request->is(array('post', 'put'))) {
            if ($this->Post->editPost($id, $this->request->data, $user)) {
                $this->Session->setFlash('Post atualizado com sucesso!');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash('Erro ao atualizar post.');
        }

        if (!$this->request->data) $this->request->data = $post;
    }

    // Deletar post (soft delete)
    public function delete($id)
    {
        $user = $this->Auth->user();
        if ($this->Post->deletePost($id, $user)) {
            $this->Session->setFlash('Post deletado com sucesso!');
        } else {
            $this->Session->setFlash('Não foi possível deletar o post.');
        }
        return $this->redirect(array('action' => 'index'));
    }

    // Publicar post
    public function publish($id)
    {
        $user = $this->Auth->user();
        if ($this->Post->publish($id, $user)) {
            $this->Session->setFlash('Post publicado com sucesso!');
        } else {
            $this->Session->setFlash('Não foi possível publicar.');
        }
        return $this->redirect(array('action' => 'index'));
    }
}
