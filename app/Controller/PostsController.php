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
        $this->set('posts', $this->Post->find('all'));
    }
    public function add() {
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
}