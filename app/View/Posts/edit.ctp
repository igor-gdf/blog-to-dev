<div class="p-4">
    <h2>Editar Post</h2>
    
    <form action="<?= $this->Html->url(array('controller' => 'posts', 'action' => 'edit', $post['Post']['id'])) ?>" method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control" id="title" name="data[Post][title]" value="<?= h($post['Post']['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Conteúdo</label>
            <textarea class="form-control" id="content" name="data[Post][content]" rows="8" required><?= h($post['Post']['content']) ?></textarea>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Status Atual:</label>
            <span class="badge bg-<?= $post['Post']['status'] === 'published' ? 'success' : 'warning' ?>">
                <?= $post['Post']['status'] === 'published' ? 'Publicado' : 'Rascunho' ?>
            </span>
        </div>
        
        <input type="hidden" name="data[Post][status]" value="<?= h($post['Post']['status']) ?>" id="post-status">
        
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-dark" data-status="published">
                <i class="bi bi-send"></i> Publicar
            </button>
            <button type="submit" class="btn btn-outline-dark" data-status="draft">
                <i class="bi bi-save"></i> Salvar como Rascunho
            </button>
            <?= $this->Html->link('Cancelar', ['action' => 'view', $post['Post']['id']], ['class' => 'btn btn-outline-secondary']) ?>
            
            <?= $this->Form->postLink(
                'Excluir Post',
                ['action' => 'delete', $post['Post']['id']],
                [
                    'class' => 'btn btn-danger ms-auto',
                    'confirm' => 'Tem certeza que deseja excluir este post?'
                ]
            ) ?>
        </div>
    </form>
</div>