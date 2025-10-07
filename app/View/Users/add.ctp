<?php
/**
 * View para cadastro de usuários
 */
?>
<div class="users form">
    <h2><?php echo __('Cadastro de Usuário'); ?></h2>
    
    <?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Preencha suas informações'); ?></legend>
        
        <?php echo $this->Form->input('username', array(
            'label' => 'Nome de Usuário',
            'class' => 'form-control',
            'required' => true
        )); ?>
        
        <?php echo $this->Form->input('email', array(
            'label' => 'E-mail',
            'type' => 'email',
            'class' => 'form-control',
            'required' => true
        )); ?>
        
        <?php echo $this->Form->input('password', array(
            'label' => 'Senha',
            'type' => 'password',
            'class' => 'form-control',
            'required' => true
        )); ?>
        
        <?php echo $this->Form->input('password_confirm', array(
            'label' => 'Confirmar Senha',
            'type' => 'password',
            'class' => 'form-control',
            'required' => true
        )); ?>
        
        <?php if ($this->Session->read('Auth.User.role') == 'admin'): ?>
        <?php echo $this->Form->input('role', array(
            'label' => 'Perfil',
            'type' => 'select',
            'options' => array(
                'user' => 'Usuário',
                'admin' => 'Administrador'
            ),
            'default' => 'user',
            'class' => 'form-control'
        )); ?>
        <?php endif; ?>
    </fieldset>
    
    <div class="form-actions">
        <?php echo $this->Form->button(__('Cadastrar'), array('class' => 'btn btn-primary', 'type' => 'submit')); ?>
        <?php echo $this->Html->link(__('Cancelar'), array('action' => 'login'), array('class' => 'btn btn-default')); ?>
    </div>
    
    <?php echo $this->Form->end(); ?>
</div>