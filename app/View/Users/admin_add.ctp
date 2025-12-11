<h2>Criar Novo Usuário</h2>

<?php echo $this->Form->create('User', ['class' => 'needs-validation', 'novalidate' => true]); ?>

<div class="mb-3">
    <?php echo $this->Form->input('username', [
        'label' => 'Nome de usuário',
        'class' => 'form-control',
        'required' => true,
        'placeholder' => 'Digite o nome de usuário'
    ]); ?>
</div>

<div class="mb-3">
    <?php echo $this->Form->input('password', [
        'label' => 'Senha',
        'type' => 'password',
        'class' => 'form-control',
        'required' => true,
        'placeholder' => 'Digite a senha'
    ]); ?>
</div>

<div class="mb-3">
    <?php echo $this->Form->input('confirm_password', [
        'label' => 'Confirmar senha',
        'type' => 'password',
        'class' => 'form-control',
        'required' => true,
        'placeholder' => 'Confirme a senha'
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
    <?php echo $this->Form->button('Criar Usuário', [
        'type' => 'submit',
        'class' => 'btn btn-success'
    ]); ?>

    <?php echo $this->Html->link('Cancelar', [
        'action' => 'admin_index'
    ], [
        'class' => 'btn btn-outline-dark'
    ]); ?>
</div>

<?php echo $this->Form->end(); ?>