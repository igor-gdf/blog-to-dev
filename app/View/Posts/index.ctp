<div class="container-fluid p-0 min-vh-100">
    <!-- Navbar -->
    <nav class="navbar bg-black border-bottom border-dark p-4">
        <div>
            <?php echo $this->Html->link(
                $this->Html->image('logo.svg', array('alt' => 'Logo')),
                array('controller' => 'posts', 'action' => 'index'),
                array('class' => 'navbar-brand', 'escape' => false)
            ); ?>
        </div>
    </nav>

    <div class="row g-0">
        <!-- Sidebar -->
        <nav id="sidebar" class=" col-md-3 col-lg-2 d-md-block bg-black sidebar border-end border-dark">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <?php echo $this->Html->link(
                            '<i class="fas fa-home text-white"></i><span class="sidebar-text text-white ms-2">Home</span>',
                            array('controller' => 'posts', 'action' => 'index'),
                            array('class' => 'nav-link', 'escape' => false)
                        ); ?>
                    </li>
                    <li class="nav-item">
                        <?php echo $this->Html->link(
                            '<i class="fas fa-users text-white"></i><span class="sidebar-text text-white ms-2">Usuários</span>',
                            array('controller' => 'users', 'action' => 'index'),
                            array('class' => 'nav-link', 'escape' => false)
                        ); ?>
                    </li>
                    <li class="nav-item">
                        <?php echo $this->Html->link(
                            '<i class="fas fa-cog text-white"></i><span class="sidebar-text text-white ms-2">Sobre o dev</span>',
                            '#',
                            array('class' => 'nav-link', 'escape' => false)
                        ); ?>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 col-lg-10 bg-light">
            <div class="p-4">
                <div class="row">
                    <div class="col-12">
                        <p>Conteúdo principal aqui...</p>

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>