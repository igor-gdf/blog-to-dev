<h1><?php echo h($post['Post']['title']); ?></h1>
<p><?= h($post['Post']['content']); ?></p>
<p><small class="text-muted">Autor: <?= h($post['User']['username']) ?> | Criado em: <?= h($post['Post']['created']) ?></small></p>
