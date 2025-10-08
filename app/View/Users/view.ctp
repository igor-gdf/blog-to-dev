<?php
$isOwnProfile = $currentUser['id'] == $user['User']['id'];
$title = $isOwnProfile ? 'Meu Perfil' : 'Perfil de ' . h($user['User']['username']);
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-user text-primary"></i> 
                    <?php echo $title; ?>
                </h4>
                <div>
                    <?php if ($isOwnProfile || $currentUser['role'] === 'admin'): ?>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-edit"></i> Editar',
                            array('action' => 'edit', $user['User']['id']),
                            array('class' => 'btn btn-primary btn-sm', 'escape' => false)
                        ); ?>
                    <?php endif; ?>
                    
                    <?php if ($currentUser['role'] === 'admin'): ?>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-list"></i> Ver Todos',
                            array('action' => 'index'),
                            array('class' => 'btn btn-secondary btn-sm ml-2', 'escape' => false)
                        ); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Informações Básicas</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nome de Usuário:</strong></td>
                                <td><?php echo h($user['User']['username']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>E-mail:</strong></td>
                                <td><?php echo h($user['User']['email']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Perfil:</strong></td>
                                <td>
                                    <?php if ($user['User']['role'] === 'admin'): ?>
                                        <span class="badge badge-danger">
                                            <i class="fas fa-crown"></i> Administrador
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-primary">
                                            <i class="fas fa-user"></i> Usuário
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Datas</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Cadastrado em:</strong></td>
                                <td><?php echo $this->Time->format('d/m/Y H:i', $user['User']['created']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Última atualização:</strong></td>
                                <td><?php echo $this->Time->format('d/m/Y H:i', $user['User']['modified']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($recentPosts) && !empty($recentPosts)): ?>
            <!-- Posts Recentes -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt text-info"></i> 
                        Posts Recentes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentPosts as $post): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <?php echo $this->Html->link(
                                        h($post['Post']['title']),
                                        array('controller' => 'posts', 'action' => 'view', $post['Post']['id']),
                                        array('class' => 'font-weight-bold')
                                    ); ?>
                                    <br>
                                    <small class="text-muted">
                                        <?php echo $this->Time->format('d/m/Y H:i', $post['Post']['created']); ?>
                                    </small>
                                </div>
                                <div>
                                    <?php if ($post['Post']['status'] === 'published'): ?>
                                        <span class="badge badge-success">Publicado</span>
                                    <?php elseif ($post['Post']['status'] === 'draft'): ?>
                                        <span class="badge badge-warning">Rascunho</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary"><?php echo h($post['Post']['status']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-center mt-3">
                        <?php echo $this->Html->link(
                            'Ver Todos os Posts',
                            array('controller' => 'posts', 'action' => 'index', '?' => array('user_id' => $user['User']['id'])),
                            array('class' => 'btn btn-outline-info btn-sm')
                        ); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <?php if (isset($postStats)): ?>
            <!-- Estatísticas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar text-success"></i> 
                        Estatísticas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border-right">
                                <h4 class="text-primary"><?php echo $postStats['total']; ?></h4>
                                <small class="text-muted">Total de Posts</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-right">
                                <h4 class="text-success"><?php echo $postStats['published']; ?></h4>
                                <small class="text-muted">Publicados</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning"><?php echo $postStats['draft']; ?></h4>
                            <small class="text-muted">Rascunhos</small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Ações -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs text-secondary"></i> 
                    Ações
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if ($isOwnProfile): ?>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-plus"></i> Criar Post',
                            array('controller' => 'posts', 'action' => 'add'),
                            array('class' => 'btn btn-success btn-block mb-2', 'escape' => false)
                        ); ?>
                        
                        <?php echo $this->Html->link(
                            '<i class="fas fa-list"></i> Meus Posts',
                            array('controller' => 'posts', 'action' => 'index'),
                            array('class' => 'btn btn-primary btn-block mb-2', 'escape' => false)
                        ); ?>
                        
                        <?php echo $this->Html->link(
                            '<i class="fas fa-tachometer-alt"></i> Dashboard',
                            array('controller' => 'dashboard', 'action' => 'index'),
                            array('class' => 'btn btn-info btn-block mb-2', 'escape' => false)
                        ); ?>
                    <?php else: ?>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-eye"></i> Ver Posts do Usuário',
                            array('controller' => 'posts', 'action' => 'index', '?' => array('user_id' => $user['User']['id'])),
                            array('class' => 'btn btn-primary btn-block mb-2', 'escape' => false)
                        ); ?>
                    <?php endif; ?>
                    
                    <?php if ($currentUser['role'] === 'admin' && $user['User']['role'] !== 'admin' && !$isOwnProfile): ?>
                        <div class="dropdown-divider"></div>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-trash text-danger"></i> Deletar Usuário',
                            array('action' => 'delete', $user['User']['id']),
                            array(
                                'class' => 'btn btn-outline-danger btn-block',
                                'escape' => false,
                                'confirm' => 'Tem certeza que deseja deletar este usuário? Esta ação não pode ser desfeita.'
                            )
                        ); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Informações Adicionais -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle text-info"></i> 
                    Informações
                </h6>
            </div>
            <div class="card-body">
                <small class="text-muted">
                    <strong>Membro desde:</strong><br>
                    <?php echo $this->Time->format('F Y', $user['User']['created']); ?>
                    <br><br>
                    
                    <?php if ($user['User']['role'] === 'admin'): ?>
                        <i class="fas fa-crown text-warning"></i> 
                        Este usuário possui privilégios de administrador e pode gerenciar todos os aspectos do sistema.
                    <?php else: ?>
                        <i class="fas fa-user text-primary"></i> 
                        Este usuário pode criar e gerenciar seus próprios posts.
                    <?php endif; ?>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Voltar -->
<div class="mt-4 text-center">
    <?php echo $this->Html->link(
        '<i class="fas fa-arrow-left"></i> Voltar',
        'javascript:history.back()',
        array('class' => 'btn btn-secondary', 'escape' => false)
    ); ?>
</div>