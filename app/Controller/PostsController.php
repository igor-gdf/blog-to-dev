<?php
App::uses('AppController', 'Controller');

class PostsController extends AppController
{
    public $uses = array('Post');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('view', 'public_index'); // Permite acesso público à view e listagem pública
    }

    // Lista posts com filtros para usuários logados
    public function index()
    {
        $user = $this->Auth->user();
        $filters = array();
        
        // Captura filtros da URL/POST
        if ($this->request->is('post') || !empty($this->request->query)) {
            $data = !empty($this->request->data) ? $this->request->data : $this->request->query;
            
            if (!empty($data['search'])) {
                $filters['search'] = trim($data['search']);
            }
            if (!empty($data['status'])) {
                $filters['status'] = $data['status'];
            }
            
            // Processa data inicial
            if (!empty($data['date_start'])) {
                if (is_array($data['date_start'])) {
                    $year = !empty($data['date_start']['year']) ? $data['date_start']['year'] : '';
                    $month = !empty($data['date_start']['month']) ? str_pad($data['date_start']['month'], 2, '0', STR_PAD_LEFT) : '';
                    $day = !empty($data['date_start']['day']) ? str_pad($data['date_start']['day'], 2, '0', STR_PAD_LEFT) : '';
                    
                    if ($year && $month && $day) {
                        $filters['date_start'] = $year . '-' . $month . '-' . $day;
                    }
                } else {
                    $filters['date_start'] = $data['date_start'];
                }
            }
            
            // Processa data final
            if (!empty($data['date_end'])) {
                if (is_array($data['date_end'])) {
                    $year = !empty($data['date_end']['year']) ? $data['date_end']['year'] : '';
                    $month = !empty($data['date_end']['month']) ? str_pad($data['date_end']['month'], 2, '0', STR_PAD_LEFT) : '';
                    $day = !empty($data['date_end']['day']) ? str_pad($data['date_end']['day'], 2, '0', STR_PAD_LEFT) : '';
                    
                    if ($year && $month && $day) {
                        $filters['date_end'] = $year . '-' . $month . '-' . $day;
                    }
                } else {
                    $filters['date_end'] = $data['date_end'];
                }
            }
            
            if (!empty($data['author_id'])) {
                $filters['author_id'] = $data['author_id'];
            }
        }
        
        // Configuração de paginação
        $this->Paginator->settings = array(
            'Post' => array(
                'limit' => 10,
                'conditions' => $this->Post->applyFilters($filters, $user),
                'contain' => array('User'),
                'order' => array('Post.created' => 'DESC')
            )
        );
        
        $posts = $this->Paginator->paginate('Post');
        
        // Lista de autores para o filtro (apenas para admins)
        $authors = array();
        if ($user['role'] === 'admin') {
            $authors = $this->Post->User->find('list', array(
                'fields' => array('User.id', 'User.username'),
                'order' => array('User.username' => 'ASC')
            ));
        }
        
        $this->set(compact('posts', 'filters', 'authors'));
        $this->set('auth_user', $user);
    }

    // Lista pública de posts (para visitantes)
    public function public_index()
    {
        $filters = array();
        
        // Captura filtros da URL
        if (!empty($this->request->query)) {
            $data = $this->request->query;
            
            if (!empty($data['search'])) {
                $filters['search'] = trim($data['search']);
            }
            
            // Processa data inicial
            if (!empty($data['date_start'])) {
                if (is_array($data['date_start'])) {
                    $year = !empty($data['date_start']['year']) ? $data['date_start']['year'] : '';
                    $month = !empty($data['date_start']['month']) ? str_pad($data['date_start']['month'], 2, '0', STR_PAD_LEFT) : '';
                    $day = !empty($data['date_start']['day']) ? str_pad($data['date_start']['day'], 2, '0', STR_PAD_LEFT) : '';
                    
                    if ($year && $month && $day) {
                        $filters['date_start'] = $year . '-' . $month . '-' . $day;
                    }
                } else {
                    $filters['date_start'] = $data['date_start'];
                }
            }
            
            // Processa data final
            if (!empty($data['date_end'])) {
                if (is_array($data['date_end'])) {
                    $year = !empty($data['date_end']['year']) ? $data['date_end']['year'] : '';
                    $month = !empty($data['date_end']['month']) ? str_pad($data['date_end']['month'], 2, '0', STR_PAD_LEFT) : '';
                    $day = !empty($data['date_end']['day']) ? str_pad($data['date_end']['day'], 2, '0', STR_PAD_LEFT) : '';
                    
                    if ($year && $month && $day) {
                        $filters['date_end'] = $year . '-' . $month . '-' . $day;
                    }
                } else {
                    $filters['date_end'] = $data['date_end'];
                }
            }
        }
        
        // Força apenas posts publicados para visitantes
        $filters['status'] = 'published';
        
        // Configuração de paginação
        $this->Paginator->settings = array(
            'Post' => array(
                'limit' => 10,
                'conditions' => $this->Post->applyFilters($filters, null),
                'contain' => array('User'),
                'order' => array('Post.created' => 'DESC')
            )
        );
        
        $posts = $this->Paginator->paginate('Post');
        
        $this->set(compact('posts', 'filters'));
        $this->set('isPublicView', true);
    }

    // Visualizar um post
    public function view($id)
    {
        $user = $this->Auth->user();
        $post = $this->Post->find('first', array(
            'conditions' => array('Post.id' => $id),
            'contain' => array('User')
        ));
        
        if (!$post) {
            throw new NotFoundException('Post não encontrado');
        }
        
        // Verifica se o post foi deletado e se o usuário tem permissão para ver
        if ($post['Post']['deleted_at']) {
            if (!$user || ($user['role'] !== 'admin' && $post['Post']['user_id'] != $user['id'])) {
                throw new NotFoundException('Post não encontrado');
            }
        }
        
        // Visitantes só podem ver posts publicados
        if (!$user && $post['Post']['status'] !== 'published') {
            throw new NotFoundException('Post não encontrado');
        }
        
        // Usuários comuns só podem ver posts publicados ou seus próprios posts
        if ($user && $user['role'] !== 'admin' && $post['Post']['user_id'] != $user['id'] && $post['Post']['status'] !== 'published') {
            throw new NotFoundException('Post não encontrado');
        }
        
        $this->set('post', $post);
        $this->set('auth_user', $user);
    }

    // Adicionar post
    public function add()
    {
        $user = $this->Auth->user();
        
        if ($this->request->is('post')) {
            $this->request->data['Post']['user_id'] = $user['id'];
            
            // Se não especificado, define como rascunho
            if (empty($this->request->data['Post']['status'])) {
                $this->request->data['Post']['status'] = 'draft';
            }
            
            $this->Post->create();
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash(
                    'Post criado com sucesso!', 
                    'default', 
                    array('class' => 'alert alert-success')
                );
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                'Erro ao criar post. Verifique os dados e tente novamente.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
        }
        
        $this->set('auth_user', $user);
    }

    // Editar post
    public function edit($id)
    {
        $user = $this->Auth->user();
        $post = $this->Post->findById($id);

        if (!$post) throw new NotFoundException('Post não encontrado');

        if ($this->request->is(array('post', 'put'))) {
            if ($this->Post->editPost($id, $this->request->data, $user)) {
                $this->Session->setFlash(
                    'Post atualizado com sucesso!', 
                    'default', 
                    array('class' => 'alert alert-success')
                );
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                'Erro ao atualizar post. Verifique os dados e tente novamente.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
        }

        if (!$this->request->data) $this->request->data = $post;
    }

    // Deletar post (soft delete)
    public function delete($id)
    {
        $user = $this->Auth->user();
        if ($this->Post->deletePost($id, $user)) {
            $this->Session->setFlash(
                'Post deletado com sucesso!', 
                'default', 
                array('class' => 'alert alert-success')
            );
        } else {
            $this->Session->setFlash(
                'Não foi possível deletar o post. Verifique suas permissões.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
        }
        return $this->redirect(array('action' => 'index'));
    }

    // Publicar post
    public function publish($id)
    {
        $user = $this->Auth->user();
        if ($this->Post->publish($id, $user)) {
            $this->Session->setFlash(
                'Post publicado com sucesso!', 
                'default', 
                array('class' => 'alert alert-success')
            );
        } else {
            $this->Session->setFlash(
                'Não foi possível publicar o post. Verifique suas permissões.', 
                'default', 
                array('class' => 'alert alert-danger')
            );
        }
        return $this->redirect(array('action' => 'index'));
    }
}
