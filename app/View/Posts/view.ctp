<?php
$this->set('title_for_layout', h($post['Post']['title']) . ' - Blog CakePHP');
?>

<div class="row">
    <div class="col-lg-8">
        <!-- Post Content -->
        <article class="card">
            <div class="card-body">
                <!-- Post Header -->
                <div class="mb-4">
                    <h1 class="display-4 mb-3"><?php echo h($post['Post']['title']); ?></h1>
                    
                    <div class="post-meta border-bottom pb-3 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <span class="text-muted">
                                    <i class="fas fa-user"></i> 
                                    Por <strong><?php echo h($post['User']['username']); ?></strong>
                                </span>
                                <span class="text-muted ml-3">
                                    <i class="fas fa-calendar"></i> 
                                    <?php echo $this->Time->format('d/m/Y H:i', $post['Post']['created']); ?>
                                </span>
                                <?php if ($post['Post']['created'] !== $post['Post']['modified']): ?>
                                    <span class="text-muted ml-3">
                                        <i class="fas fa-edit"></i> 
                                        Atualizado em <?php echo $this->Time->format('d/m/Y H:i', $post['Post']['modified']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 text-md-right">
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
                                }
                                ?>
                                <span class="badge badge-<?php echo $statusClass; ?> badge-lg">
                                    <i class="fas <?php echo $statusIcon; ?>"></i> 
                                    <?php echo ucfirst(h($post['Post']['status'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Post Content -->
                <div class="post-content">
                    <?php echo nl2br(h($post['Post']['content'])); ?>
                </div>
            </div>
        </article>

        <!-- Navigation -->
        <div class="mt-4">
            <div class="row">
                <div class="col-6">
                    <?php if (isset($auth_user) && !empty($auth_user)): ?>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-arrow-left"></i> Voltar aos Posts', 
                            array('controller' => 'posts', 'action' => 'index'),
                            array('class' => 'btn btn-secondary', 'escape' => false)
                        ); ?>
                    <?php else: ?>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-arrow-left"></i> Voltar ao Blog', 
                            '/',
                            array('class' => 'btn btn-secondary', 'escape' => false)
                        ); ?>
                    <?php endif; ?>
                </div>
                <div class="col-6 text-right">
                    <?php if (isset($auth_user) && !empty($auth_user) && 
                             ($auth_user['role'] === 'admin' || $auth_user['id'] == $post['Post']['user_id'])): ?>
                        
                        <?php echo $this->Html->link(
                            '<i class="fas fa-edit"></i> Editar', 
                            array('action' => 'edit', $post['Post']['id']),
                            array('class' => 'btn btn-warning mr-2', 'escape' => false)
                        ); ?>
                        
                        <?php if ($post['Post']['status'] === 'draft'): ?>
                            <?php echo $this->Html->link(
                                '<i class="fas fa-paper-plane"></i> Publicar', 
                                array('action' => 'publish', $post['Post']['id']),
                                array('class' => 'btn btn-success mr-2', 'escape' => false)
                            ); ?>
                        <?php endif; ?>
                        
                        <?php echo $this->Form->postLink(
                            '<i class="fas fa-trash"></i> Excluir', 
                            array('action' => 'delete', $post['Post']['id']),
                            array(
                                'class' => 'btn btn-danger btn-delete',
                                'escape' => false,
                                'confirm' => 'Tem certeza que deseja excluir o post "' . $post['Post']['title'] . '"?'
                            )
                        ); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Author Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-user-circle"></i> Sobre o Autor</h6>
            </div>
            <div class="card-body">
                <div class="media">
                    <div class="media-body">
                        <h6 class="mt-0"><?php echo h($post['User']['username']); ?></h6>
                        <p class="text-muted mb-0">
                            <small>
                                <i class="fas fa-crown text-warning"></i>
                                <?php echo $post['User']['role'] === 'admin' ? 'Administrador' : 'Autor'; ?>
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Post Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informações do Post</h6>
            </div>
            <div class="card-body">
                <small class="text-muted">
                    <div class="mb-2">
                        <i class="fas fa-hashtag"></i> 
                        <strong>ID:</strong> #<?php echo h($post['Post']['id']); ?>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-calendar-plus"></i> 
                        <strong>Criado:</strong><br>
                        <?php echo $this->Time->format('d/m/Y H:i', $post['Post']['created']); ?>
                    </div>
                    <?php if ($post['Post']['created'] !== $post['Post']['modified']): ?>
                        <div class="mb-2">
                            <i class="fas fa-calendar-check"></i> 
                            <strong>Modificado:</strong><br>
                            <?php echo $this->Time->format('d/m/Y H:i', $post['Post']['modified']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-2">
                        <i class="fas fa-file-alt"></i> 
                        <strong>Caracteres:</strong> <?php echo mb_strlen($post['Post']['content']); ?>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-paragraph"></i> 
                        <strong>Palavras:</strong> <?php echo str_word_count(strip_tags($post['Post']['content'])); ?>
                    </div>
                </small>
            </div>
        </div>

        <!-- Actions for Guests -->
        <?php if (!isset($auth_user) || empty($auth_user)): ?>
            <div class="card">
                <div class="card-body text-center">
                    <h6><i class="fas fa-user-plus text-success"></i> Gostou do conteúdo?</h6>
                    <p class="text-muted small">
                        Cadastre-se para criar e gerenciar seus próprios posts!
                    </p>
                    <div class="d-grid gap-2">
                        <?php echo $this->Html->link(
                            '<i class="fas fa-user-plus"></i> Cadastrar-se', 
                            '/register',
                            array('class' => 'btn btn-success btn-sm btn-block mb-2', 'escape' => false)
                        ); ?>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-sign-in-alt"></i> Fazer Login', 
                            '/login',
                            array('class' => 'btn btn-outline-primary btn-sm btn-block', 'escape' => false)
                        ); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>