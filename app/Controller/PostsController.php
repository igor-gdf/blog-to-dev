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
}