<h1>Usuários</h1>
<table class="table">
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
            <td><?= h($user['User']['role']) ?></td>
            <td><?= h($user['User']['created']) ?></td>
            <td>
                <?= $this->Html->link('Editar', ['action' => 'admin_edit', $user['User']['id']], ['class' => 'btn bg-black text-white btn-sm']) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>