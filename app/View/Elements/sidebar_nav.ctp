<div class="container-fluid p-0 min-vh-100">
    <?php
    // evita erro quando usuário não estiver logado
    $username = $this->Session->read('Auth.User.username');
    $role = $this->Session->read('Auth.User.role');
    $loggedIn = !empty($username);
    ?>
    <!-- Navbar -->
    <nav class="navbar bg-black border-bottom border-dark p-4">
        <?php echo $this->Session->flash(); ?>
        <div>
            <?php echo $this->Html->link(
                $this->Html->image('logo.svg', array('alt' => 'Logo')),
                array('controller' => 'posts', 'action' => 'index'),
                array('class' => 'navbar-brand d-none d-md-flex', 'escape' => false)
            ); ?>
            <?php echo $this->Html->link(
                $this->Html->image('logomob.svg', array('alt' => 'Logo')),
                array('controller' => 'posts', 'action' => 'index'),
                array('class' => 'navbar-brand d-md-none', 'escape' => false)
            ); ?>
        </div>
        <div class="d-flex">
            <!--<?= $this->Html->image('user.png', array('alt' => 'User Icon', 'class' => 'me-2')); ?>-->
            <?php if ($loggedIn): ?>
                <div class="me-4">
                    <b class="text-white">Bem-vindo, <?= h($username); ?> | <?= ucfirst(h($role)); ?></b>
                </div>
            <?php else: ?>
                <span class="text-white">Bem-vindo, visitante!</span>
            <?php endif; ?>
        </div>
    </nav>
    <!-- Sidebar -->
    <div class="row g-0">
        <nav id="sidebar"
            class="d-none d-md-flex flex-column justify-content-between col-md-3 col-lg-2 bg-black border-end border-dark">

            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <?php echo $this->Html->link($this->Html->image('home.svg', ['alt' => 'Home', 'class' => 'me-2 mb-1']) . 'Home', array('controller' => 'posts', 'action' => 'index'), array('class' => 'nav-link d-inline-flex align-items-center text-white', 'escape' => false)); ?>
                    </li>
                    <li class="nav-item">
                        <?php echo $this->Html->link($this->Html->image('dashboard.svg', ['alt' => 'Dashboard', 'class' => 'me-2 mb-1']) . 'Dashboard', array('controller' => 'posts', 'action' => 'dashboard'), array('class' => 'nav-link d-inline-flex align-items-center text-white', 'escape' => false)); ?>
                    </li>
                    <li class="nav-item">
                        <?php echo $this->Html->link($this->Html->image('perfil.svg', ['alt' => 'Perfil', 'class' => 'me-2 mb-1']) . 'Perfil', array('controller' => 'users', 'action' => 'profile'), array('class' => 'nav-link d-inline-flex align-items-center text-white', 'escape' => false)); ?>
                    </li>
                    <?php if ($loggedIn && $role === 'admin'): ?>
                        <li class="nav-item">
                            <?php echo $this->Html->link($this->Html->image('users.svg', ['alt' => 'Usuários', 'class' => 'me-2 mb-1']) . 'Usuários', array('controller' => 'users', 'action' => 'admin_index'), array('class' => 'nav-link d-inline-flex align-items-center text-white', 'escape' => false)); ?>
                        </li>

                        <li class="nav-item">
                            <?php echo $this->Html->link($this->Html->image('list.svg', ['alt' => 'Lista', 'class' => 'me-2 mb-1']) . 'All posts', array('controller' => 'posts', 'action' => 'admin_index'), array('class' => 'nav-link d-inline-flex align-items-center text-white', 'escape' => false)); ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="position-sticky pb-3">
                <ul class="nav flex-column">
                    <?php if ($loggedIn): ?>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                $this->Html->image('logout.svg', ['alt' => 'Logout', 'class' => 'me-2']) . 'Logout',
                                array('controller' => 'users', 'action' => 'logout'),
                                array('class' => 'nav-link d-inline-flex align-items-center text-danger', 'escape' => false)
                            ) ?>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                $this->Html->image('login.svg', ['alt' => 'Login', 'class' => 'me-2']) . 'Login',
                                array('controller' => 'users', 'action' => 'login'),
                                array('class' => 'nav-link d-inline-flex align-items-center  text-success', 'escape' => false)
                            ) ?>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item text-center">
                        <small>
                            copyright©blogtodev-2025
                        </small>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Barra inferior fixa, inspirada na barra lateral -->
        <nav class="navbar navbar-expand bg-black border-top border-dark fixed-bottom d-md-none p-3">
            <ul class="navbar-nav d-flex flex-row justify-content-around w-100">
                <li class="nav-item">
                    <?php echo $this->Html->link(
                        $this->Html->image('home.svg', ['alt' => 'Home', 'class' => 'me-1 mb-1']) . '',
                        ['controller' => 'posts', 'action' => 'index'],
                        ['class' => 'nav-link text-white px-2', 'escape' => false]
                    ); ?>
                </li>
                <li class="nav-item">
                    <?php echo $this->Html->link(
                        $this->Html->image('dashboard.svg', ['alt' => 'Dashboard', 'class' => 'me-1 mb-1']) . '',
                        ['controller' => 'posts', 'action' => 'dashboard'],
                        ['class' => 'nav-link text-white px-2', 'escape' => false]
                    ); ?>
                </li>
                <li class="nav-item">
                    <?php echo $this->Html->link(
                        $this->Html->image('perfil.svg', ['alt' => 'Perfil', 'class' => 'me-1 mb-1']) . '',
                        ['controller' => 'users', 'action' => 'profile'],
                        ['class' => 'nav-link text-white px-2', 'escape' => false]
                    ); ?>
                </li>
                <?php if ($loggedIn && $role === 'admin'): ?>
                    <li class="nav-item">
                        <?php echo $this->Html->link(
                            $this->Html->image('users.svg', ['alt' => 'Usuários', 'class' => 'me-1 mb-1']) . '',
                            ['controller' => 'users', 'action' => 'admin_index'],
                            ['class' => 'nav-link text-warning px-2', 'escape' => false]
                        ); ?>
                    </li>
                    <li class="nav-item">
                        <?php echo $this->Html->link(
                            $this->Html->image('list.svg', ['alt' => 'Lista', 'class' => 'me-1 mb-1']) . '',
                            ['controller' => 'posts', 'action' => 'admin_index'],
                            ['class' => 'nav-link text-white px-2', 'escape' => false]
                        ); ?>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <?php if ($loggedIn): ?>
                        <?= $this->Html->link(
                            $this->Html->image('logout.svg', ['alt' => 'Logout', 'class' => 'me-1']) . '',
                            ['controller' => 'users', 'action' => 'logout'],
                            ['class' => 'nav-link text-danger px-2', 'escape' => false]
                        ) ?>
                    <?php else: ?>
                        <?= $this->Html->link(
                            $this->Html->image('login.svg', ['alt' => 'Login', 'class' => 'me-1']) . '',
                            ['controller' => 'users', 'action' => 'login'],
                            ['class' => 'nav-link text-success px-2', 'escape' => false]
                        ) ?>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>