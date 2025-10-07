<?php
/**
 * View para listagem de posts
 */
?>
<div class="posts index">
    <h2><?php echo __('Posts'); ?></h2>
    
    <p>
        <?php echo $this->Html->link(__('Novo Post'), array('action' => 'add'), array('class' => 'btn btn-primary')); ?>
    </p>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('id'); ?></th>
                <th><?php echo $this->Paginator->sort('title', 'Título'); ?></th>
                <th><?php echo $this->Paginator->sort('status'); ?></th>
                <th><?php echo $this->Paginator->sort('User.username', 'Autor'); ?></th>
                <th><?php echo $this->Paginator->sort('created', 'Criado em'); ?></th>
                <th class="actions"><?php echo __('Ações'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
            <tr>
                <td><?php echo h($post['Post']['id']); ?></td>
                <td>
                    <?php echo $this->Html->link(h($post['Post']['title']), array('action' => 'view', $post['Post']['id'])); ?>
                </td>
                <td>
                    <span class="label <?php echo $post['Post']['status'] == 'published' ? 'label-success' : ($post['Post']['status'] == 'draft' ? 'label-warning' : 'label-danger'); ?>">
                        <?php echo h($post['Post']['status']); ?>
                    </span>
                </td>
                <td><?php echo h($post['User']['username']); ?></td>
                <td><?php echo h($post['Post']['created']); ?></td>
                <td class="actions">
                    <?php echo $this->Html->link(__('Ver'), array('action' => 'view', $post['Post']['id']), array('class' => 'btn btn-sm btn-info')); ?>
                    <?php if ($this->Session->read('Auth.User.role') == 'admin' || $this->Session->read('Auth.User.id') == $post['Post']['user_id']): ?>
                        <?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $post['Post']['id']), array('class' => 'btn btn-sm btn-warning')); ?>
                        <?php echo $this->Form->postLink(__('Excluir'), array('action' => 'delete', $post['Post']['id']), array('class' => 'btn btn-sm btn-danger', 'confirm' => __('Tem certeza que deseja excluir este post?'))); ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php if (empty($posts)): ?>
    <div class="alert alert-info">
        <p><?php echo __('Nenhum post encontrado.'); ?></p>
    </div>
    <?php endif; ?>
</div>