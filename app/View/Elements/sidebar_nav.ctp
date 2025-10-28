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
                array('class' => 'navbar-brand', 'escape' => false)
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

    <div class="row g-0">
        <!-- Sidebar -->
        <nav id="sidebar"
            class="d-flex justify-content-between flex-column col-md-3 col-lg-2 bg-black sidebar border-end border-dark">
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