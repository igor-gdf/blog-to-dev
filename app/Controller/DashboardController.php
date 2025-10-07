<?php
App::uses('AppController', 'Controller');

class DashboardController extends AppController
{
    public $uses = array('Post', 'User');

    public function beforeFilter()
    {
        parent::beforeFilter();
        // Dashboard requer login
    }

    /**
     * Dashboard principal - estatísticas e resumos
     */
    public function index()
    {
        $user = $this->Auth->user();
        $stats = array();

        if ($user['role'] === 'admin') {
            // Estatísticas para admin
            $stats = array(
                'total_posts' => $this->Post->find('count', array(
                    'conditions' => array('Post.deleted_at' => null)
                )),
                'published_posts' => $this->Post->find('count', array(
                    'conditions' => array(
                        'Post.status' => 'published',
                        'Post.deleted_at' => null
                    )
                )),
                'draft_posts' => $this->Post->find('count', array(
                    'conditions' => array(
                        'Post.status' => 'draft',
                        'Post.deleted_at' => null
                    )
                )),
                'total_users' => $this->User->find('count'),
                'admin_users' => $this->User->find('count', array(
                    'conditions' => array('User.role' => 'admin')
                ))
            );

            // Posts recentes (todos)
            $recent_posts = $this->Post->find('all', array(
                'conditions' => array('Post.deleted_at' => null),
                'contain' => array('User'),
                'order' => array('Post.created' => 'DESC'),
                'limit' => 5
            ));

            // Usuários recentes
            $recent_users = $this->User->find('all', array(
                'fields' => array('id', 'username', 'email', 'role', 'created'),
                'order' => array('User.created' => 'DESC'),
                'limit' => 5
            ));

            $this->set(compact('recent_users'));
        } else {
            // Estatísticas para usuário comum
            $stats = array(
                'my_total_posts' => $this->Post->find('count', array(
                    'conditions' => array(
                        'Post.user_id' => $user['id'],
                        'Post.deleted_at' => null
                    )
                )),
                'my_published_posts' => $this->Post->find('count', array(
                    'conditions' => array(
                        'Post.user_id' => $user['id'],
                        'Post.status' => 'published',
                        'Post.deleted_at' => null
                    )
                )),
                'my_draft_posts' => $this->Post->find('count', array(
                    'conditions' => array(
                        'Post.user_id' => $user['id'],
                        'Post.status' => 'draft',
                        'Post.deleted_at' => null
                    )
                ))
            );

            // Meus posts recentes
            $recent_posts = $this->Post->find('all', array(
                'conditions' => array(
                    'Post.user_id' => $user['id'],
                    'Post.deleted_at' => null
                ),
                'contain' => array('User'),
                'order' => array('Post.created' => 'DESC'),
                'limit' => 5
            ));
        }

        $this->set(compact('stats', 'recent_posts'));
        $this->set('auth_user', $user);
    }
}