<div class="p-4">
    <h2>Editar Usuário</h2>

    <div class="card">
        <div class="card-body">
            <?php
            echo $this->Form->create('User', [
                'url' => ['action' => 'admin_edit', $user['User']['id']],
                'class' => 'needs-validation'
            ]);
            ?>

            <div class="mb-3">
                <?php echo $this->Form->input('username', [
                    'label' => 'Nome de usuário',
                    'class' => 'form-control',
                    'required' => true
                ]); ?>
            </div>

            <div class="mb-3">
                <?php echo $this->Form->input('password', [
                    'label' => 'Nova senha (deixe em branco para não alterar)',
                    'type' => 'password',
                    'class' => 'form-control',
                    'required' => false,
                    'value' => ''
                ]); ?>
            </div>

            <div class="mb-3">
                <?php echo $this->Form->input('role', [
                    'label' => 'Perfil',
                    'type' => 'select',
                    'options' => ['author' => 'Autor', 'admin' => 'Administrador'],
                    'class' => 'form-select',
                    'empty' => false
                ]); ?>
            </div>

            <div class="d-flex gap-2">
                <?php echo $this->Form->button('Salvar Alterações', [
                    'type' => 'submit',
                    'class' => 'btn btn-dark'
                ]); ?>
                
                <?php echo $this->Html->link('Cancelar', [
                    'action' => 'admin_index'
                ], [
                    'class' => 'btn btn-outline-dark'
                ]); ?>
                
                <?php if ($user['User']['role'] !== 'admin'): ?>
                    <?php echo $this->Form->postLink(
                        'Excluir Usuário',
                        ['action' => 'admin_delete', $user['User']['id']],
                        [
                            'confirm' => 'Tem certeza que deseja excluir este usuário?',
                            'class' => 'btn btn-danger ms-auto'
                        ]
                    ); ?>
                <?php endif; ?>
            </div>

            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>