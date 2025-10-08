<div class="users form">
    <?php echo $this->Session->flash();?>

    <?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Por favor, insira seu nome de usuário e senha'); ?></legend>
        <?php
            echo $this->Form->input('username', array(
                'required' => true, 
                'label' => 'Usuário',
                'div' => array('class' => 'form-group'),
                'class' => 'form-control'
            ));
            echo $this->Form->input('password', array(
                'required' => true, 
                'label' => 'Senha',
                'div' => array('class' => 'form-group'),
                'class' => 'form-control'
            ));
            echo $this->Form->input('confirm_password', array(
                'type' => 'password',
                'required' => true, 
                'label' => 'Confirmar Senha',
                'div' => array('class' => 'form-group'),
                'class' => 'form-control'
            ));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Registrar'), array('class' => 'btn btn-primary')); ?>
</div>