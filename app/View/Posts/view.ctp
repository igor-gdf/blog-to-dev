<?php
/**
 * View para visualização de post
 */
?>
<div class="posts view">
    <h2><?php echo h($post['Post']['title']); ?></h2>
    
    <div class="post-meta">
        <p>
            <strong>Autor:</strong> <?php echo h($post['User']['username']); ?> |
            <strong>Status:</strong> 
            <span class="label <?php echo $post['Post']['status'] == 'published' ? 'label-success' : ($post['Post']['status'] == 'draft' ? 'label-warning' : 'label-danger'); ?>">
                <?php echo h($post['Post']['status']); ?>
            </span> |
            <strong>Criado em:</strong> <?php echo h($post['Post']['created']); ?>
        </p>
    </div>
    
    <div class="post-content">
        <?php echo nl2br(h($post['Post']['content'])); ?>
    </div>
    
    <div class="actions">
        <?php echo $this->Html->link('← Voltar para Posts', array('action' => 'index'), array('class' => 'btn btn-default')); ?>
        
        <?php if ($this->Session->read('Auth.User.role') == 'admin' || $this->Session->read('Auth.User.id') == $post['Post']['user_id']): ?>
            <?php echo $this->Html->link('Editar', array('action' => 'edit', $post['Post']['id']), array('class' => 'btn btn-warning')); ?>
            <?php echo $this->Form->postLink('Excluir', array('action' => 'delete', $post['Post']['id']), array('class' => 'btn btn-danger', 'confirm' => 'Tem certeza que deseja excluir este post?')); ?>
        <?php endif; ?>
    </div>
</div>