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
    <div class="row g-0">
        <?php if (empty($hideSidebar)): ?>
            <?= $this->element('sidebar_nav') ?>
        <?php endif; ?>
        <main id="main-content" class="<?= empty($hideSidebar) ? 'col-md-9 col-lg-10' : 'col-12' ?> bg-light d-flex flex-column" style="max-height: calc(100vh - var(--navbar-height, 0px)); overflow: hidden;">
            <div class="p-4 flex-grow-1 overflow-auto" style="max-height: calc(100vh - var(--navbar-height, 0px));">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </main>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function() {
        var nav = document.querySelector('.navbar');
        var root = document.documentElement;
        if (nav && root) {
            var h = nav.getBoundingClientRect().height;
            root.style.setProperty('--navbar-height', h + 'px');
        }
    })();
</script>
<script src="<?= $this->Html->url('/js/index.js'); ?>"></script>
</body>
</html>
