<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gerenciar Usuários</h2>
        <?= $this->Html->link('+ Criar Usuário', ['action' => 'admin_add'], ['class' => 'btn btn-primary']) ?>
    </div>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Perfil</th>
                <th>Criado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= h($user['User']['id']) ?></td>
                <td><?= h($user['User']['username']) ?></td>
                <td>
                    <span class="badge bg-<?= $user['User']['role'] === 'admin' ? 'danger' : 'info' ?>">
                        <?= h($user['User']['role']) ?>
                    </span>
                </td>
                <td><?= $this->Time->format('d/m/Y H:i', $user['User']['created']) ?></td>
                <td>
                    <?= $this->Html->link('Editar', 
                        ['action' => 'admin_edit', $user['User']['id']], 
                        ['class' => 'btn btn-sm btn-warning']
                    ) ?>
                    
                    <?php if ($user['User']['role'] !== 'admin'): ?>
                        <?= $this->Form->postLink('Excluir',
                            ['action' => 'admin_delete', $user['User']['id']],
                            [
                                'class' => 'btn btn-sm btn-danger',
                                'confirm' => 'Tem certeza que deseja excluir este usuário?'
                            ]
                        ) ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>