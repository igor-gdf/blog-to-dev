<h1>Editar Usuário</h1>

<div class="card">
    <div class="card-body">
        <?php
        echo $this->Form->create('User', [
            'url' => ['action' => 'admin_edit', $user['User']['id']]
        ]);

        echo $this->Form->input('username', [
            'label' => 'Username',
            'class' => 'form-control'
        ]);

        echo $this->Form->input('role', [
            'label' => 'Perfil',
            'type' => 'select',
            // Atenção à estrutura correta: 'valor mostrado' => 'valor salvo'
            'options' => ['author' => 'Author', 'admin' => 'Admin'],
            'class' => 'form-control'
        ]);

        echo $this->Form->submit('Salvar', ['class' => 'btn btn-primary mt-3']);
        echo $this->Form->end();
        echo $this->Form->postLink(
            'Excluir Usuário',
            ['action' => 'admin_delete', $user['User']['id']],
            [
                'confirm' => 'Tem certeza que deseja excluir este usuário?',
                'class' => 'btn btn-danger'
            ]
        );
        ?>
    </div>

</div>