
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
                    'class' => 'btn btn-success'
                ]); ?>
                
                <?php echo $this->Html->link('Cancelar', [
                    'action' => 'admin_index'
                ], [
                    'class' => 'btn btn-secondary'
                ]); ?>
            </div>

            <?php echo $this->Form->end(); ?>
        </div>
    </div>