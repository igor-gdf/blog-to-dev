<form action="<?= $this->Html->url(array('controller' => 'posts', 'action' => 'edit', $post['Post']['id'])) ?>" method="post">
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control" id="title" name="data[Post][title]" value="<?= h($post['Post']['title']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Conteúdo</label>
        <textarea class="form-control" id="content" name="data[Post][content]" rows="5" required><?= h($post['Post']['content']) ?></textarea>
    </div>
    <input type="hidden" name="data[Post][status]" value="published">
    <button type="submit" class="btn border-black text-black mb-3" onclick="setStatus('published')">Publicar edição</button>
    <button type="submit" class="btn border-black text-black mb-3" onclick="setStatus('draft')">Salvar como rascunho</button>
</form>

<script>
    function setStatus(status) {
        document.querySelector('input[name="data[Post][status]"]').value = status;
    }
</script>

</script>