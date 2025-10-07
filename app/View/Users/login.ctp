<?php
$this->set('title_for_layout', 'Login - Blog CakePHP');
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-0">
                    <i class="fas fa-sign-in-alt text-primary"></i> 
                    Fazer Login
                </h4>
            </div>
            <div class="card-body">
                <?php echo $this->Form->create('User', array(
                    'class' => 'needs-validation',
                    'novalidate' => true
                )); ?>
                
                <div class="form-group">
                    <?php echo $this->Form->label('username', 'Usuário', array('class' => 'form-label')); ?>
                    <?php echo $this->Form->input('username', array(
                        'type' => 'text',
                        'class' => 'form-control',
                        'placeholder' => 'Digite seu usuário',
                        'required' => true,
                        'label' => false,
                        'div' => false,
                        'autofocus' => true
                    )); ?>
                </div>
                
                <div class="form-group">
                    <?php echo $this->Form->label('password', 'Senha', array('class' => 'form-label')); ?>
                    <?php echo $this->Form->input('password', array(
                        'type' => 'password',
                        'class' => 'form-control',
                        'placeholder' => 'Digite sua senha',
                        'required' => true,
                        'label' => false,
                        'div' => false
                    )); ?>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> Entrar
                    </button>
                </div>
                
                <?php echo $this->Form->end(); ?>
                
                <hr>
                
                <div class="text-center">
                    <p class="mb-0">
                        <small class="text-muted">
                            Não tem uma conta? 
                            <?php echo $this->Html->link(
                                'Cadastre-se aqui', 
                                '/register',
                                array('class' => 'text-decoration-none')
                            ); ?>
                        </small>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Demo Users Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle text-info"></i> Contas de Demonstração</h6>
            </div>
            <div class="card-body">
                <small class="text-muted">
                    <div class="mb-2">
                        <strong>Admin:</strong><br>
                        Usuário: <code>admin</code><br>
                        Senha: <code>password</code>
                    </div>
                    <div>
                        <strong>Usuário:</strong><br>
                        Usuário: <code>user1</code><br>
                        Senha: <code>password</code>
                    </div>
                </small>
            </div>
        </div>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script>
$(document).ready(function() {
    // Validação do form
    $('form').on('submit', function(e) {
        var username = $('#UserUsername').val().trim();
        var password = $('#UserPassword').val().trim();
        
        if (username === '' || password === '') {
            e.preventDefault();
            alert('Por favor, preencha usuário e senha.');
            return false;
        }
    });
    
    // Demo login buttons
    $('.demo-login').on('click', function(e) {
        e.preventDefault();
        var username = $(this).data('username');
        var password = $(this).data('password');
        
        $('#UserUsername').val(username);
        $('#UserPassword').val(password);
        
        $('form').submit();
    });
});
</script>
<?php $this->end(); ?>