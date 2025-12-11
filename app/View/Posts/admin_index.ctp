<form method="get" class="mb-3 d-flex gap-2">
    <select name="status" class="form-control w-auto">
        <option value="">Todos</option>
        <option value="published" <?= ($this->request->query('status') === 'published') ? 'selected' : '' ?>>Ativo</option>
        <option value="draft" <?= ($this->request->query('status') === 'draft') ? 'selected' : '' ?>>Rascunho</option>
    </select>
    <button type="submit" class="btn btn-dark">Filtrar</button>
</form>

<div class="row g-3" style="max-height: 60vh;">
    <?php foreach ($allPosts as $post): ?>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-truncate mb-2">
                        <?= $this->Html->link(h($post['Post']['title']), ['controller' => 'posts', 'action' => 'view', $post['Post']['id']], ['escape' => false, 'class' => 'text-black stretched-link text-decoration-none']) ?>
                    </h5>
                    <p class="card-text text-truncate mb-3"
                        style="--bs-line-clamp:3; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;">
                        <?= h($post['Post']['content']) ?>
                    </p>
                    <small class="text-muted">Autor: <?= h($post['User']['username']) ?></small>
                    <div class="card-footer bg-transparent border-0 p-0 d-flex justify-content-between">
                        <small class="text-muted"><?= h($post['Post']['created']) ?></small>
                        <small class=""><?= ucfirst(h($post['Post']['status'])) ?></small>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>