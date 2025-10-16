<?= $this->Html->link('Novo Post', ['controller' => 'posts', 'action' => 'add'], ['class' => 'btn btn-zzz mb-3']) ?>

<?php if (!empty($posts)) : ?>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Posts</h1>
    </div>

    <div class="overflow-auto row g-3" style="max-height: 55vh;">
        <?php foreach ($posts as $post) : ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2">
                            <?= $this->Html->link(h($post['Post']['title']), ['controller' => 'posts', 'action' => 'view', $post['Post']['id']], ['escape' => false, 'class' => 'stretched-link text-decoration-none']) ?>
                        </h5>
                        <p class="card-text text-truncate mb-3" style="--bs-line-clamp:3; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;">
                            <?= h($post['Post']['content']) ?>
                        </p>
                        <div class="mt-auto">
                            <small class="text-muted">Autor: <?= h($post['User']['username']) ?></small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <small class="text-muted"><?= h($post['Post']['created']) ?></small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <p>Nenhum post encontrado.</p>
<?php endif; ?>