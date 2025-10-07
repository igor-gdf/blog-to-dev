<?php
/**
 * View para login de usuários
 */
?>
<div class="users form">
    <h2><?php echo __('Login'); ?></h2>
    
    <?php echo $this->Form->create('User', array('action' => 'login')); ?>
    <fieldset>
        <legend><?php echo __('Entre com suas credenciais'); ?></legend>
        
        <?php echo $this->Form->input('username', array(
            'label' => 'Nome de Usuário',
            'class' => 'form-control',
            'required' => true
        )); ?>
        
        <?php echo $this->Form->input('password', array(
            'label' => 'Senha',
            'type' => 'password',
            'class' => 'form-control',
            'required' => true
        )); ?>
    </fieldset>
    
    <div class="form-actions">
        <?php echo $this->Form->button(__('Entrar'), array('class' => 'btn btn-primary', 'type' => 'submit')); ?>
        <?php echo $this->Html->link(__('Cadastrar-se'), array('action' => 'add'), array('class' => 'btn btn-link')); ?>
    </div>
    
    <?php echo $this->Form->end(); ?>
</div>