<div class="user-profile">
    <h2><?php echo __('Perfil de Usuário'); ?></h2>

    <p><strong><?php echo __('Nome de Usuário:'); ?></strong> <?php echo h($user['User']['username']); ?></p>
    <p><strong><?php echo __('ID do Usuário:'); ?></strong> <?php echo h($user['User']['id']); ?></p>
    <p><strong><?php echo __('Criado em:'); ?></strong> <?php echo h($user['User']['created']); ?></p>
    <p><strong><?php echo __('Modificado em:'); ?></strong> <?php echo h($user['User']['modified']); ?></p>

    <button></button>

    <?php if ($this->Auth->user('id') == $user['User']['id']): ?>
        <p>
            <?php echo $this->Html->link(__('Editar Perfil'), array('action' => 'editProfile', $user['User']['id']), array('class' => 'btn btn-primary')); ?>
            <?php echo $this->Form->postLink(
                __('Deletar Conta'),
                array('action' => 'delete', $user['User']['id']),
                array('class' => 'btn btn-danger'),
                __('Você tem certeza que deseja deletar sua conta? Esta ação não pode ser desfeita.')
            ); ?>
        </p>
    <?php endif; ?>