<h2>Criar Novo Post</h2>

<form action="<?= $this->Html->url(array('controller' => 'posts', 'action' => 'add')) ?>" method="post">
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control" id="title" name="data[Post][title]" required>
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Conteúdo</label>
        <textarea class="form-control" id="content" name="data[Post][content]" rows="8" required></textarea>
    </div>

    <input type="hidden" name="data[Post][status]" value="published" id="post-status">

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success" data-status="published">
            <i class="bi bi-send"></i> Publicar Post
        </button>
        <button type="submit" class="btn btn-warning" data-status="draft">
            <i class="bi bi-save"></i> Salvar como Rascunho
        </button>
        <?= $this->Html->link('Cancelar', ['action' => 'dashboard'], ['class' => 'btn btn-secondary']) ?>
    </div>
</form>