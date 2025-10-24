<div class="dashboard">
    <h1 class="h3 mb-3">Dashboard</h1>
    <p>Bem-vindo ao seu painel de controle! Aqui você pode gerenciar seus posts, visualizar estatísticas e acessar
        outras funcionalidades importantes do blog.</p>
    <div class="mb-4">
        <h2 class="h5">Estatísticas dos Posts</h2>
        <ul>
            <li>Total de Posts: <?= h($totalPosts) ?></li>
            <li>Posts Publicados: <?= h($publishedPosts) ?></li>
            <li>Posts em Rascunho: <?= h($draftPosts) ?></li>
        </ul>
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

<script>


    function setClassColor(element, status) {
        if (status === 'published') {
            element.classList.add('text-success');
        }else if (status === 'draft') {
            element.classList.add('text-warning');
        }else {
            element.classList.add('text-danger');
        }
    }

    document.querySelectorAll('.card-footer small:nth-child(2)').forEach(function(element) {
        var status = element.textContent.trim().toLowerCase();
        setClassColor(element, status);
    });
</script>