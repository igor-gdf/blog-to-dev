<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php echo $this->Html->charset(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo isset($title_for_layout) ? $title_for_layout : 'Blog CakePHP'; ?>
    </title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .post-meta {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .status-badge {
            font-size: 0.75rem;
        }
        footer {
            background-color: #343a40;
            color: white;
            margin-top: 3rem;
        }
        .sidebar {
            background-color: white;
            border-radius: 0.25rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .btn-action {
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
        }
        .flash-message {
            margin-bottom: 1rem;
        }
    </style>
    
    <?php
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
    ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <?php echo $this->Html->link(
                '<i class="fas fa-blog"></i> Blog CakePHP', 
                '/', 
                array('class' => 'navbar-brand', 'escape' => false)
            ); ?>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <?php echo $this->Html->link(
                            '<i class="fas fa-home"></i> Início', 
                            '/', 
                            array('class' => 'nav-link', 'escape' => false)
                        ); ?>
                    </li>
                    <?php if (isset($loggedInUser) && !empty($loggedInUser)): ?>
                        <li class="nav-item">
                            <?php echo $this->Html->link(
                                '<i class="fas fa-tachometer-alt"></i> Dashboard', 
                                '/dashboard', 
                                array('class' => 'nav-link', 'escape' => false)
                            ); ?>
                        </li>
                        <li class="nav-item">
                            <?php echo $this->Html->link(
                                '<i class="fas fa-edit"></i> Meus Posts', 
                                '/posts', 
                                array('class' => 'nav-link', 'escape' => false)
                            ); ?>
                        </li>
                        <?php if ($loggedInUser['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <?php echo $this->Html->link(
                                    '<i class="fas fa-users"></i> Usuários', 
                                    '/admin/users', 
                                    array('class' => 'nav-link', 'escape' => false)
                                ); ?>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isset($loggedInUser) && !empty($loggedInUser)): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                                <i class="fas fa-user"></i> 
                                <?php echo h($loggedInUser['username']); ?>
                                <span class="badge badge-<?php echo $loggedInUser['role'] === 'admin' ? 'warning' : 'info'; ?> ml-1">
                                    <?php echo $loggedInUser['role']; ?>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php echo $this->Html->link(
                                    '<i class="fas fa-user-edit"></i> Meu Perfil', 
                                    array('controller' => 'users', 'action' => 'edit', $loggedInUser['id']), 
                                    array('class' => 'dropdown-item', 'escape' => false)
                                ); ?>
                                <div class="dropdown-divider"></div>
                                <?php echo $this->Html->link(
                                    '<i class="fas fa-sign-out-alt"></i> Sair', 
                                    '/logout', 
                                    array('class' => 'dropdown-item', 'escape' => false)
                                ); ?>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <?php echo $this->Html->link(
                                '<i class="fas fa-sign-in-alt"></i> Login', 
                                '/login', 
                                array('class' => 'nav-link', 'escape' => false)
                            ); ?>
                        </li>
                        <li class="nav-item">
                            <?php echo $this->Html->link(
                                '<i class="fas fa-user-plus"></i> Cadastrar', 
                                '/register', 
                                array('class' => 'nav-link', 'escape' => false)
                            ); ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Flash Messages -->
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->Session->flash('auth'); ?>
        
        <!-- Page Content -->
        <?php echo $this->fetch('content'); ?>
    </div>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Blog CakePHP 2.x</h5>
                    <p class="mb-0">Sistema de blog desenvolvido em CakePHP com PostgreSQL e Docker.</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <p class="mb-0">
                        <small>
                            Desenvolvido com <i class="fas fa-heart text-danger"></i> 
                            em <?php echo date('Y'); ?>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Confirm delete actions
        $('.btn-delete').click(function(e) {
            if (!confirm('Tem certeza que deseja excluir este item?')) {
                e.preventDefault();
            }
        });
        
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
    </script>
    
    <?php echo $this->fetch('scriptBottom'); ?>
</body>
</html>
