<?php
// Dashboard principal
?>

<div class="row">
    <div class="col-12 mb-4">
        <h1>
            <i class="fas fa-tachometer-alt text-primary"></i> 
            Dashboard
            <small class="text-muted">
                - Bem-vindo, <?php echo h($auth_user['username']); ?>!
            </small>
        </h1>
    </div>
</div>

<!-- Estatísticas -->
<div class="row mb-4">
    <?php if ($auth_user['role'] === 'admin'): ?>
        <!-- Admin Stats -->
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Posts</h5>
                            <h2 class="mb-0"><?php echo number_format($stats['total_posts']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Publicados</h5>
                            <h2 class="mb-0"><?php echo number_format($stats['published_posts']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Rascunhos</h5>
                            <h2 class="mb-0"><?php echo number_format($stats['draft_posts']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-edit fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Usuários</h5>
                            <h2 class="mb-0"><?php echo number_format($stats['total_users']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- User Stats -->
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Meus Posts</h5>
                            <h2 class="mb-0"><?php echo number_format($stats['my_total_posts']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Publicados</h5>
                            <h2 class="mb-0"><?php echo number_format($stats['my_published_posts']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Rascunhos</h5>
                            <h2 class="mb-0"><?php echo number_format($stats['my_draft_posts']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-edit fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Content Grid -->
<div class="row">
    <!-- Recent Posts -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clock text-info"></i> 
                    <?php echo $auth_user['role'] === 'admin' ? 'Posts Recentes' : 'Meus Posts Recentes'; ?>
                </h5>
                <?php echo $this->Html->link(
                    'Ver todos <i class="fas fa-arrow-right"></i>', 
                    array('controller' => 'posts', 'action' => 'index'),
                    array('class' => 'btn btn-sm btn-outline-primary', 'escape' => false)
                ); ?>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_posts)): ?>
                    <?php foreach ($recent_posts as $post): ?>
                        <div class="media mb-3 pb-3 border-bottom">
                            <div class="media-body">
                                <h6 class="mt-0">
                                    <?php echo $this->Html->link(
                                        h($post['Post']['title']), 
                                        array('controller' => 'posts', 'action' => 'view', $post['Post']['id']),
                                        array('class' => 'text-dark')
                                    ); ?>
                                    <?php
                                    $statusClass = $post['Post']['status'] === 'published' ? 'success' : 'warning';
                                    ?>
                                    <span class="badge badge-<?php echo $statusClass; ?> ml-2">
                                        <?php echo ucfirst($post['Post']['status']); ?>
                                    </span>
                                </h6>
                                <small class="text-muted">
                                    Por <?php echo h($post['User']['username']); ?> 
                                    em <?php echo $this->Time->format('d/m/Y H:i', $post['Post']['created']); ?>
                                </small>
                                <p class="mt-1 mb-0 text-muted small">
                                    <?php 
                                    $content = strip_tags($post['Post']['content']);
                                    echo h(mb_substr($content, 0, 100)) . (mb_strlen($content) > 100 ? '...' : '');
                                    ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h6>Nenhum post encontrado</h6>
                        <p class="text-muted">
                            <?php echo $this->Html->link(
                                'Criar meu primeiro post', 
                                array('controller' => 'posts', 'action' => 'add'),
                                array('class' => 'btn btn-primary btn-sm')
                            ); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Info -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt text-warning"></i> Ações Rápidas</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php echo $this->Html->link(
                        '<i class="fas fa-plus"></i> Novo Post', 
                        array('controller' => 'posts', 'action' => 'add'),
                        array('class' => 'btn btn-success btn-block mb-2', 'escape' => false)
                    ); ?>
                    
                    <?php echo $this->Html->link(
                        '<i class="fas fa-list"></i> Meus Posts', 
                        array('controller' => 'posts', 'action' => 'index'),
                        array('class' => 'btn btn-primary btn-block mb-2', 'escape' => false)
                    ); ?>
                    
                    <?php echo $this->Html->link(
                        '<i class="fas fa-globe"></i> Ver Blog Público', 
                        '/',
                        array('class' => 'btn btn-outline-info btn-block mb-2', 'escape' => false, 'target' => '_blank')
                    ); ?>
                    
                    <?php if ($auth_user['role'] === 'admin'): ?>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-users"></i> Gerenciar Usuários', 
                            array('controller' => 'users', 'action' => 'index'),
                            array('class' => 'btn btn-warning btn-block', 'escape' => false)
                        ); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($auth_user['role'] === 'admin' && isset($recent_users)): ?>
            <!-- Recent Users (Admin only) -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-user-plus text-success"></i> Usuários Recentes</h6>
                    <?php echo $this->Html->link(
                        'Ver todos', 
                        array('controller' => 'users', 'action' => 'index'),
                        array('class' => 'btn btn-sm btn-outline-secondary')
                    ); ?>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_users)): ?>
                        <?php foreach ($recent_users as $user): ?>
                            <div class="media mb-2">
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1">
                                        <?php echo h($user['User']['username']); ?>
                                        <span class="badge badge-<?php echo $user['User']['role'] === 'admin' ? 'warning' : 'info'; ?> ml-1">
                                            <?php echo $user['User']['role']; ?>
                                        </span>
                                    </h6>
                                    <small class="text-muted">
                                        <?php echo h($user['User']['email']); ?><br>
                                        Cadastrado em <?php echo $this->Time->format('d/m/Y', $user['User']['created']); ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted small mb-0">Nenhum usuário recente.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- User Profile Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-user-circle text-info"></i> Meu Perfil</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-4x text-muted"></i>
                        <h6 class="mt-2"><?php echo h($auth_user['username']); ?></h6>
                        <span class="badge badge-<?php echo $auth_user['role'] === 'admin' ? 'warning' : 'info'; ?>">
                            <?php echo $auth_user['role'] === 'admin' ? 'Administrador' : 'Autor'; ?>
                        </span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <?php echo $this->Html->link(
                            '<i class="fas fa-edit"></i> Editar Perfil', 
                            array('controller' => 'users', 'action' => 'edit', $auth_user['id']),
                            array('class' => 'btn btn-outline-primary btn-sm btn-block', 'escape' => false)
                        ); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>