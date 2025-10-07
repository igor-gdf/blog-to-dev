<?php
$this->assign('title', 'Gerenciar Posts');
?>

<div class="row">
    <div class="col-12">
        <!-- Header da página -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-edit text-primary"></i> 
                <?php echo $auth_user['role'] === 'admin' ? 'Todos os Posts' : 'Meus Posts'; ?>
            </h1>
            <?php echo $this->Html->link(
                '<i class="fas fa-plus"></i> Novo Post', 
                array('action' => 'add'),
                array('class' => 'btn btn-success', 'escape' => false)
            ); ?>
        </div>

        <!-- Filtros de busca -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-filter"></i> Filtros</h6>
            </div>
            <div class="card-body">
                <?php echo $this->Form->create('Post', array(
                    'type' => 'get',
                    'inputDefaults' => array(
                        'label' => false,
                        'div' => false
                    )
                )); ?>
                
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <?php echo $this->Form->input('search', array(
                            'type' => 'text',
                            'placeholder' => 'Buscar por título ou conteúdo...',
                            'class' => 'form-control',
                            'value' => isset($filters['search']) ? $filters['search'] : ''
                        )); ?>
                    </div>
                    <div class="col-md-2 mb-2">
                        <?php echo $this->Form->input('status', array(
                            'type' => 'select',
                            'options' => array(
                                '' => 'Todos os status',
                                'published' => 'Publicados',
                                'draft' => 'Rascunhos',
                                'active' => 'Ativos',
                                'inactive' => 'Inativos'
                            ),
                            'class' => 'form-control',
                            'value' => isset($filters['status']) ? $filters['status'] : ''
                        )); ?>
                    </div>
                    <?php if ($auth_user['role'] === 'admin' && !empty($authors)): ?>
                        <div class="col-md-2 mb-2">
                            <?php echo $this->Form->input('author_id', array(
                                'type' => 'select',
                                'options' => array('' => 'Todos os autores') + $authors,
                                'class' => 'form-control',
                                'value' => isset($filters['author_id']) ? $filters['author_id'] : ''
                            )); ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-2 mb-2">
                        <?php echo $this->Form->input('date_start', array(
                            'type' => 'text',
                            'placeholder' => 'Data inicial (AAAA-MM-DD)',
                            'class' => 'form-control',
                            'value' => isset($filters['date_start']) ? $filters['date_start'] : '',
                            'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}'
                        )); ?>
                    </div>
                    <div class="col-md-2 mb-2">
                        <?php echo $this->Form->input('date_end', array(
                            'type' => 'text',
                            'placeholder' => 'Data final (AAAA-MM-DD)',
                            'class' => 'form-control',
                            'value' => isset($filters['date_end']) ? $filters['date_end'] : '',
                            'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}'
                        )); ?>
                    </div>
                    <div class="col-md-1 mb-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>

        <!-- Lista de posts -->
        <?php if (!empty($posts)): ?>
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->Paginator->sort('id', '#'); ?></th>
                                <th><?php echo $this->Paginator->sort('title', 'Título'); ?></th>
                                <th><?php echo $this->Paginator->sort('status', 'Status'); ?></th>
                                <th><?php echo $this->Paginator->sort('User.username', 'Autor'); ?></th>
                                <th><?php echo $this->Paginator->sort('created', 'Criado em'); ?></th>
                                <th width="200">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><strong>#<?php echo h($post['Post']['id']); ?></strong></td>
                                <td>
                                    <?php echo $this->Html->link(
                                        h($post['Post']['title']), 
                                        array('action' => 'view', $post['Post']['id']),
                                        array('class' => 'text-dark font-weight-bold')
                                    ); ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = '';
                                    $statusIcon = '';
                                    switch($post['Post']['status']) {
                                        case 'published':
                                            $statusClass = 'success';
                                            $statusIcon = 'fa-check-circle';
                                            break;
                                        case 'draft':
                                            $statusClass = 'warning';
                                            $statusIcon = 'fa-edit';
                                            break;
                                        case 'deleted':
                                            $statusClass = 'danger';
                                            $statusIcon = 'fa-trash';
                                            break;
                                        default:
                                            $statusClass = 'secondary';
                                            $statusIcon = 'fa-question';
                                    }
                                    ?>
                                    <span class="badge badge-<?php echo $statusClass; ?>">
                                        <i class="fas <?php echo $statusIcon; ?>"></i> 
                                        <?php echo ucfirst(h($post['Post']['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-user text-muted"></i> 
                                    <?php echo h($post['User']['username']); ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo $this->Time->format('d/m/Y H:i', $post['Post']['created']); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php echo $this->Html->link(
                                        '<i class="fas fa-eye"></i>', 
                                        array('action' => 'view', $post['Post']['id']),
                                        array(
                                            'class' => 'btn btn-sm btn-outline-info btn-action',
                                            'escape' => false,
                                            'data-toggle' => 'tooltip',
                                            'title' => 'Visualizar'
                                        )
                                    ); ?>
                                    
                                    <?php if ($auth_user['role'] === 'admin' || $auth_user['id'] == $post['Post']['user_id']): ?>
                                        <?php echo $this->Html->link(
                                            '<i class="fas fa-edit"></i>', 
                                            array('action' => 'edit', $post['Post']['id']),
                                            array(
                                                'class' => 'btn btn-sm btn-outline-warning btn-action',
                                                'escape' => false,
                                                'data-toggle' => 'tooltip',
                                                'title' => 'Editar'
                                            )
                                        ); ?>
                                        
                                        <?php if ($post['Post']['status'] === 'draft'): ?>
                                            <?php echo $this->Html->link(
                                                '<i class="fas fa-paper-plane"></i>', 
                                                array('action' => 'publish', $post['Post']['id']),
                                                array(
                                                    'class' => 'btn btn-sm btn-outline-success btn-action',
                                                    'escape' => false,
                                                    'data-toggle' => 'tooltip',
                                                    'title' => 'Publicar'
                                                )
                                            ); ?>
                                        <?php endif; ?>
                                        
                                        <?php echo $this->Form->postLink(
                                            '<i class="fas fa-trash"></i>', 
                                            array('action' => 'delete', $post['Post']['id']),
                                            array(
                                                'class' => 'btn btn-sm btn-outline-danger btn-action btn-delete',
                                                'escape' => false,
                                                'data-toggle' => 'tooltip',
                                                'title' => 'Excluir',
                                                'confirm' => 'Tem certeza que deseja excluir o post "' . $post['Post']['title'] . '"?'
                                            )
                                        ); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Paginação -->
            <?php if ($this->Paginator->hasPage(2)): ?>
                <nav aria-label="Navegação dos posts" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php
                        echo $this->Paginator->prev(
                            '<i class="fas fa-chevron-left"></i> Anterior', 
                            array('tag' => 'li', 'class' => 'page-item', 'escape' => false),
                            null,
                            array('tag' => 'li', 'class' => 'page-item disabled', 'escape' => false)
                        );
                        
                        echo $this->Paginator->numbers(array(
                            'tag' => 'li',
                            'currentTag' => 'a',
                            'currentClass' => 'page-item active',
                            'class' => 'page-item'
                        ));
                        
                        echo $this->Paginator->next(
                            'Próximo <i class="fas fa-chevron-right"></i>', 
                            array('tag' => 'li', 'class' => 'page-item', 'escape' => false),
                            null,
                            array('tag' => 'li', 'class' => 'page-item disabled', 'escape' => false)
                        );
                        ?>
                    </ul>
                </nav>
                
                <div class="text-center text-muted">
                    <?php echo $this->Paginator->counter(
                        'Página {:page} de {:pages}, mostrando {:current} posts de {:count} total'
                    ); ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h3>Nenhum post encontrado</h3>
                    <p class="text-muted">
                        <?php if (!empty($filters)): ?>
                            Tente ajustar os filtros de busca.
                        <?php else: ?>
                            Você ainda não criou nenhum post.
                        <?php endif; ?>
                    </p>
                    <div class="mt-3">
                        <?php echo $this->Html->link(
                            '<i class="fas fa-plus"></i> Criar meu primeiro post', 
                            array('action' => 'add'),
                            array('class' => 'btn btn-primary', 'escape' => false)
                        ); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>