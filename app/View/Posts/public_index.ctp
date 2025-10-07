<?php
$this->assign('title', 'Blog - Últimas Postagens');
?>

<div class="row">
    <div class="col-lg-8">
        <!-- Header da página -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-blog text-primary"></i> 
                Últimas Postagens
            </h1>
        </div>

        <!-- Filtros de busca -->
        <div class="card mb-4">
            <div class="card-body">
                <?php echo $this->Form->create('Post', array(
                    'type' => 'get',
                    'class' => 'form-inline',
                    'inputDefaults' => array(
                        'label' => false,
                        'div' => false
                    )
                )); ?>
                
                <div class="row w-100">
                    <div class="col-md-4 mb-2">
                        <?php echo $this->Form->input('search', array(
                            'type' => 'text',
                            'placeholder' => 'Buscar por título ou conteúdo...',
                            'class' => 'form-control',
                            'value' => isset($filters['search']) ? $filters['search'] : ''
                        )); ?>
                    </div>
                    <div class="col-md-3 mb-2">
                        <?php echo $this->Form->input('date_start', array(
                            'type' => 'text',
                            'placeholder' => 'Data inicial (AAAA-MM-DD)',
                            'class' => 'form-control',
                            'value' => isset($filters['date_start']) ? $filters['date_start'] : '',
                            'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}'
                        )); ?>
                    </div>
                    <div class="col-md-3 mb-2">
                        <?php echo $this->Form->input('date_end', array(
                            'type' => 'text',
                            'placeholder' => 'Data final (AAAA-MM-DD)',
                            'class' => 'form-control',
                            'value' => isset($filters['date_end']) ? $filters['date_end'] : '',
                            'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}'
                        )); ?>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>

        <!-- Lista de posts -->
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title">
                            <?php echo $this->Html->link(
                                h($post['Post']['title']), 
                                array('controller' => 'posts', 'action' => 'view', $post['Post']['id']),
                                array('class' => 'text-decoration-none')
                            ); ?>
                        </h2>
                        
                        <div class="post-meta mb-3">
                            <span>
                                <i class="fas fa-user text-muted"></i> 
                                Por <strong><?php echo h($post['User']['username']); ?></strong>
                            </span>
                            <span class="ml-3">
                                <i class="fas fa-calendar text-muted"></i> 
                                <?php echo $this->Time->format('d/m/Y H:i', $post['Post']['created']); ?>
                            </span>
                            <span class="badge badge-success ml-2">
                                <i class="fas fa-eye"></i> Publicado
                            </span>
                        </div>
                        
                        <div class="card-text">
                            <?php 
                            $content = strip_tags($post['Post']['content']);
                            echo h(mb_substr($content, 0, 300)) . (mb_strlen($content) > 300 ? '...' : '');
                            ?>
                        </div>
                        
                        <div class="mt-3">
                            <?php echo $this->Html->link(
                                '<i class="fas fa-arrow-right"></i> Ler mais', 
                                array('controller' => 'posts', 'action' => 'view', $post['Post']['id']),
                                array('class' => 'btn btn-outline-primary', 'escape' => false)
                            ); ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>

            <!-- Paginação -->
            <?php if ($this->Paginator->hasPage(2)): ?>
                <nav aria-label="Navegação dos posts">
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
            <?php endif; ?>

        <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h3>Nenhum post encontrado</h3>
                    <p class="text-muted">
                        <?php if (!empty($filters)): ?>
                            Tente ajustar os filtros de busca ou 
                            <?php echo $this->Html->link('limpar filtros', array('controller' => 'posts', 'action' => 'public_index')); ?>.
                        <?php else: ?>
                            Ainda não há posts publicados no blog.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="sidebar">
            <h5><i class="fas fa-info-circle text-primary"></i> Sobre o Blog</h5>
            <p class="text-muted">
                Este é um blog desenvolvido em CakePHP 2.x com funcionalidades completas 
                de gestão de posts e usuários. Explore os artigos e conteúdos disponíveis!
            </p>
            
            <?php if (!isset($loggedInUser) || empty($loggedInUser)): ?>
                <hr>
                <h6><i class="fas fa-user-plus text-success"></i> Participe!</h6>
                <p class="text-muted small">
                    Cadastre-se para criar e gerenciar seus próprios posts.
                </p>
                <div class="d-grid gap-2">
                    <?php echo $this->Html->link(
                        '<i class="fas fa-user-plus"></i> Cadastrar-se', 
                        '/register',
                        array('class' => 'btn btn-success btn-sm', 'escape' => false)
                    ); ?>
                    <?php echo $this->Html->link(
                        '<i class="fas fa-sign-in-alt"></i> Fazer Login', 
                        '/login',
                        array('class' => 'btn btn-outline-primary btn-sm ml-2', 'escape' => false)
                    ); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Stats Widget (opcional) -->
        <div class="sidebar">
            <h6><i class="fas fa-chart-bar text-info"></i> Estatísticas</h6>
            <small class="text-muted">
                <i class="fas fa-file-alt"></i> 
                Total de posts: <strong><?php echo $this->Paginator->counter('{:count}'); ?></strong>
            </small>
        </div>
    </div>
</div>