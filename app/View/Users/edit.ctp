<h2>Editar Minha Conta</h2>

<div class="card">
    <div class="card-body">
        <?php
        echo $this->Form->create('User', [
            'url' => ['action' => 'edit'],
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
            <?php echo $this->Form->input('confirm_password', [
                'label' => 'Confirmar nova senha',
                'type' => 'password',
                'class' => 'form-control',
                'required' => false
            ]); ?>
        </div>

        <div class="d-flex gap-2">
            <?php echo $this->Form->button('Salvar Alterações', [
                'type' => 'submit',
                'class' => 'btn btn-success'
            ]); ?>

            <?php echo $this->Html->link('Cancelar', [
                'action' => 'profile'
            ], [
                'class' => 'btn btn-secondary'
            ]); ?>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
</div>

<div class="card mt-3 border-danger">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Zona de Perigo</h5>
    </div>
    <div class="card-body">
        <p>Ao excluir sua conta, todos os seus dados serão permanentemente removidos.</p>
        <?php echo $this->Html->link(
            'Excluir Minha Conta',
            ['action' => 'delete'],
            [
                'class' => 'btn btn-danger',
                'data-confirm' => 'Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.'
            ]
        ); ?>
    </div>
</div>