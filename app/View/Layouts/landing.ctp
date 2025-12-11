<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->Html->charset(); ?>
    <title><?= $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon'); ?>
    <?= $this->Html->css([
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        'style'
    ]); ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>

<div class="container-fluid p-0 min-vh-100">
    <main id="main-content" class="col-12 d-flex flex-column" style="min-height: 100vh;">
        <div class="flex-grow-1">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $this->Html->url('/js/index.js'); ?>"></script>
</body>
</html>
