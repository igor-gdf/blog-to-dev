<h1>Editar Usuário</h1>
<div class="card">
    <div class="card-body">
        <?php
        echo $this->Form->create('User', [
            'url' => ['action' => 'admin_edit', $user['User']['id']]
        ]);
        echo $this->Form->input('username', [
            'label' => 'Username',
            'value' => $user['User']['username'],
            'class' => 'form-control'
        ]);
        echo $this->Form->input('role', [
            'label' => 'Perfil',
            'type' => 'select',
            'options' => ['Author' => 'author', 'Admin' => 'admin'],
            'value' => $user['User']['role'],
            'class' => 'form-control'
        ]);
        echo $this->Form->submit('Salvar', ['class' => 'btn btn-primary mt-3']);
        echo $this->Form->end();
        ?>
    </div>
</div>