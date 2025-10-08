<?php $this->set('title_for_layout', 'Gerenciar Usuários'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-users text-primary"></i> 
                    Gerenciar Usuários
                </h4>
                <?php echo $this->Html->link(
                    '<i class="fas fa-user-plus"></i> Novo Usuário',
                    array('action' => 'add'),
                    array('class' => 'btn btn-success', 'escape' => false)
                ); ?>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <?php echo $this->Form->create('Filter', array(
                            'url' => array('action' => 'index'),
                            'class' => 'form-inline',
                            'type' => 'get'
                        )); ?>
                        
                        <div class="form-group mr-3">
                            <?php echo $this->Form->input('search', array(
                                'type' => 'text',
                                'class' => 'form-control',
                                'placeholder' => 'Buscar usuário ou email...',
                                'label' => false,
                                'div' => false,
                                'value' => isset($filters['search']) ? $filters['search'] : ''
                            )); ?>
                        </div>
                        
                        <div class="form-group mr-3">
                            <?php echo $this->Form->input('role', array(
                                'type' => 'select',
                                'options' => array(
                                    '' => 'Todos os perfis',
                                    'user' => 'Usuários',
                                    'admin' => 'Administradores'
                                ),
                                'class' => 'form-control',
                                'label' => false,
                                'div' => false,
                                'value' => isset($filters['role']) ? $filters['role'] : ''
                            )); ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        
                        <?php if (!empty($filters)): ?>
                            <?php echo $this->Html->link(
                                '<i class="fas fa-times"></i> Limpar',
                                array('action' => 'index'),
                                array('class' => 'btn btn-secondary ml-2', 'escape' => false)
                            ); ?>
                        <?php endif; ?>
                        
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>

                <?php if (empty($users)): ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i>
                        <?php if (!empty($filters)): ?>
                            Nenhum usuário encontrado com os filtros aplicados.
                        <?php else: ?>
                            Nenhum usuário cadastrado ainda.
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <!-- Lista de Usuários -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>
                                        <?php echo $this->Paginator->sort('username', 'Usuário'); ?>
                                    </th>
                                    <th>
                                        <?php echo $this->Paginator->sort('email', 'E-mail'); ?>
                                    </th>
                                    <th>
                                        <?php echo $this->Paginator->sort('role', 'Perfil'); ?>
                                    </th>
                                    <th>
                                        <?php echo $this->Paginator->sort('created', 'Cadastrado'); ?>
                                    </th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo h($user['User']['username']); ?></strong>
                                            <?php if ($user['User']['id'] == $currentUser['id']): ?>
                                                <span class="badge badge-info ml-1">Você</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo h($user['User']['email']); ?></td>
                                        <td>
                                            <?php if ($user['User']['role'] === 'admin'): ?>
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-crown"></i> Admin
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-primary">
                                                    <i class="fas fa-user"></i> Usuário
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small>
                                                <?php echo $this->Time->format('d/m/Y', $user['User']['created']); ?>
                                                <br>
                                                <span class="text-muted">
                                                    <?php echo $this->Time->format('H:i', $user['User']['created']); ?>
                                                </span>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <!-- Ver Perfil -->
                                                <?php echo $this->Html->link(
                                                    '<i class="fas fa-eye"></i>',
                                                    array('action' => 'view', $user['User']['id']),
                                                    array(
                                                        'class' => 'btn btn-info btn-sm',
                                                        'title' => 'Ver Perfil',
                                                        'escape' => false
                                                    )
                                                ); ?>
                                                
                                                <!-- Editar -->
                                                <?php echo $this->Html->link(
                                                    '<i class="fas fa-edit"></i>',
                                                    array('action' => 'edit', $user['User']['id']),
                                                    array(
                                                        'class' => 'btn btn-primary btn-sm',
                                                        'title' => 'Editar',
                                                        'escape' => false
                                                    )
                                                ); ?>
                                                
                                                <!-- Deletar (apenas para não-admins e não próprio usuário) -->
                                                <?php if ($user['User']['role'] !== 'admin' && $user['User']['id'] != $currentUser['id']): ?>
                                                    <?php echo $this->Html->link(
                                                        '<i class="fas fa-trash"></i>',
                                                        array('action' => 'delete', $user['User']['id']),
                                                        array(
                                                            'class' => 'btn btn-danger btn-sm',
                                                            'title' => 'Deletar',
                                                            'escape' => false,
                                                            'confirm' => 'Tem certeza que deseja deletar o usuário "' . $user['User']['username'] . '"? Esta ação não pode ser desfeita.'
                                                        )
                                                    ); ?>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-sm" disabled title="Não é possível deletar">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <?php if ($this->Paginator->hasNext() || $this->Paginator->hasPrev()): ?>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <?php echo $this->Paginator->counter(array(
                                    'format' => 'Página {:page} de {:pages}, mostrando {:current} de {:count} usuários'
                                )); ?>
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <?php echo $this->Paginator->prev(
                                        '<i class="fas fa-chevron-left"></i>',
                                        array('tag' => 'li', 'escape' => false),
                                        null,
                                        array('tag' => 'li', 'class' => 'disabled', 'escape' => false)
                                    ); ?>
                                    
                                    <?php echo $this->Paginator->numbers(array(
                                        'tag' => 'li',
                                        'separator' => '',
                                        'currentClass' => 'active'
                                    )); ?>
                                    
                                    <?php echo $this->Paginator->next(
                                        '<i class="fas fa-chevron-right"></i>',
                                        array('tag' => 'li', 'escape' => false),
                                        null,
                                        array('tag' => 'li', 'class' => 'disabled', 'escape' => false)
                                    ); ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas -->
<?php if (!empty($users)): ?>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="fas fa-users text-primary"></i> 
                        Total de Usuários
                    </h5>
                    <h2 class="text-primary"><?php echo count($users); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="fas fa-crown text-warning"></i> 
                        Administradores
                    </h5>
                    <h2 class="text-warning">
                        <?php 
                        $adminCount = 0;
                        foreach ($users as $user) {
                            if ($user['User']['role'] === 'admin') {
                                $adminCount++;
                            }
                        }
                        echo $adminCount;
                        ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Ações Rápidas -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools text-secondary"></i> 
                    Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $this->Html->link(
                            '<i class="fas fa-user-plus"></i> Adicionar Novo Usuário',
                            array('action' => 'add'),
                            array('class' => 'btn btn-success btn-block mb-2', 'escape' => false)
                        ); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo $this->Html->link(
                            '<i class="fas fa-tachometer-alt"></i> Voltar ao Dashboard',
                            array('controller' => 'dashboard', 'action' => 'index'),
                            array('class' => 'btn btn-primary btn-block mb-2', 'escape' => false)
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>