<?php
/**
 * View para adicionar post
 */
?>
<div class="posts form">
    <h2><?php echo __('Adicionar Post'); ?></h2>
    
    <?php echo $this->Form->create('Post'); ?>
    <fieldset>
        <legend><?php echo __('Preencha as informações do post'); ?></legend>
        
        <?php echo $this->Form->input('title', array(
            'label' => 'Título',
            'class' => 'form-control',
            'required' => true
        )); ?>
        
        <?php echo $this->Form->input('content', array(
            'label' => 'Conteúdo',
            'type' => 'textarea',
            'class' => 'form-control',
            'rows' => 10,
            'required' => true
        )); ?>
        
        <?php echo $this->Form->input('status', array(
            'label' => 'Status',
            'type' => 'select',
            'options' => array(
                'draft' => 'Rascunho',
                'published' => 'Publicado'
            ),
            'default' => 'draft',
            'class' => 'form-control'
        )); ?>
    </fieldset>
    
    <div class="form-actions">
        <?php echo $this->Form->button(__('Salvar'), array('class' => 'btn btn-primary', 'type' => 'submit')); ?>
        <?php echo $this->Html->link(__('Cancelar'), array('action' => 'index'), array('class' => 'btn btn-default')); ?>
    </div>
    
    <?php echo $this->Form->end(); ?>
</div>