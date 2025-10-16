<!DOCTYPE html>
<html>
<head>
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
    <div class="row g-0">
        <?php if (empty($hideSidebar)): ?>
            <?= $this->element('sidebar_nav') ?>
        <?php endif; ?>

        <main class="<?= empty($hideSidebar) ? 'col-md-9 col-lg-10' : 'col-12' ?> bg-light">
            <div class="p-4">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </main>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
