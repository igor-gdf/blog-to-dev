<div class="user-profile">
    <p><strong><?php echo __('Nome de Usuário:'); ?></strong> <?php echo h($user['User']['username']); ?></p>
    <p><strong><?php echo __('ID do Usuário:'); ?></strong> <?php echo h($user['User']['id']); ?></p>
    <p><strong><?php echo __('Criado em:'); ?></strong> <?php echo h($user['User']['created_br']); ?></p>
    <p><strong><?php echo __('Modificado em:'); ?></strong> <?php echo h($user['User']['modified_br']); ?></p>
</div>
