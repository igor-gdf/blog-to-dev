<div class="overflow-auto row g-3" style="max-height: 83vh;">
    <h1><?php echo h($post['Post']['title']); ?></h1>
    <p><?= h($post['Post']['content']); ?></p>
    <p><small class="text-muted">Autor: <?= h($post['User']['username']) ?></small></p>
    <p><small class="text-muted">Criado em: <?= h($post['Post']['created']) ?></small></p>
    <?php if (!empty($currentUser) && $post['Post']['user_id'] == $currentUser['id']): ?>
        <div class="d-flex">
            <?= $this->Html->link(__('Editar'), ['action' => 'edit', $post['Post']['id']], ['class' => 'btn btn-outline-secondary me-2']) ?>
            <?= $this->Html->link(__('Deletar'), ['action' => 'delete', $post['Post']['id']], ['class' => 'btn btn-outline-danger me-2', 'confirm' => __('Tem certeza que deseja deletar este post?', $post['Post']['id'])]) ?>
            <?php if ($post['Post']['status'] === 'draft'): ?>
                <form action="<?= $this->Html->url(array('controller' => 'posts', 'action' => 'edit', $post['Post']['id'])) ?>"
                    method="post">
                    <input type="hidden" name="data[Post][status]" value="published">
                    <button type="submit" class="btn btn-outline-success">Publicar rascunho</button>
                </form>
                <?php else: ?>
                <form action="<?= $this->Html->url(array('controller' => 'posts', 'action' => 'edit', $post['Post']['id'])) ?>"
                    method="post">
                    <input type="hidden" name="data[Post][status]" value="draft">
                    <button type="submit" class="btn btn-outline-warning">Tornar rascunho</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>