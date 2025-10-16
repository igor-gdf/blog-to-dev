<form action="<?= $this->Html->url(array('controller' => 'posts', 'action' => 'add')) ?>" method="post">
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control" id="title" name="data[Post][title]" required>
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Conteúdo</label>
        <textarea class="form-control" id="content" name="data[Post][content]" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn border-black text-black mb-3">Criar novo post</button>
</form>