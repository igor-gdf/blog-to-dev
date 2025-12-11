<!-- Cabeçalho -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Meu Dashboard</h2>
    <?= $this->Html->link('Criar Post', ['action' => 'add'], ['class' => 'btn btn-dark']) ?>
</div>

<!-- Cards de Estatísticas -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card border-1 border-dark shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 text-muted">Total de Posts</h6>
                        <h2 class="mb-0 fw-bold"><?= h($totalPosts) ?></h2>
                    </div>
                    <div class="fs-1 text-muted">
                        <i class="bi bi-file-text"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-1 border-dark shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 text-muted">Publicados</h6>
                        <h2 class="mb-0 fw-bold"><?= h($publishedPosts) ?></h2>
                    </div>
                    <div class="fs-1 text-muted">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-1 border-dark shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 text-muted">Rascunhos</h6>
                        <h2 class="mb-0 fw-bold"><?= h($draftPosts) ?></h2>
                    </div>
                    <div class="fs-1 text-muted">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mb-3">
    <form method="get" class="d-flex gap-2">
        <select name="status" class="form-control w-auto">
            <option value="">Todos</option>
            <option value="published" <?= ($this->request->query('status') === 'published') ? 'selected' : '' ?>>Ativo
            </option>
            <option value="draft" <?= ($this->request->query('status') === 'draft') ? 'selected' : '' ?>>Rascunho
            </option>
        </select>
        <button type="submit" class="btn btn-dark">Filtrar</button>
    </form>
</div>
<div class="overflow-auto row g-3" style="max-height: 45vh;">
    <?php foreach ($myPosts as $post): ?>
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
</div>