<?php
$title = isset($currentUser) && !empty($currentUser) ? 'Adicionar Usuário' : 'Cadastro';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus text-success"></i> 
                    <?php echo $title; ?>
                </h4>
            </div>
            <div class="card-body">
                <?php echo $this->Form->create('User', array(
                    'class' => 'needs-validation',
                    'novalidate' => true
                )); ?>
                
                <div class="form-group">
                    <?php echo $this->Form->label('username', 'Nome de Usuário *', array('class' => 'form-label')); ?>
                    <?php echo $this->Form->input('username', array(
                        'type' => 'text',
                        'class' => 'form-control',
                        'placeholder' => 'Digite um nome de usuário único',
                        'required' => true,
                        'label' => false,
                        'div' => false,
                        'maxlength' => 50,
                        'pattern' => '[a-zA-Z0-9]+',
                        'title' => 'Apenas letras e números são permitidos'
                    )); ?>
                    <small class="form-text text-muted">
                        Apenas letras e números. Mínimo 3 caracteres.
                    </small>
                </div>
                
                <div class="form-group">
                    <?php echo $this->Form->label('email', 'E-mail *', array('class' => 'form-label')); ?>
                    <?php echo $this->Form->input('email', array(
                        'type' => 'email',
                        'class' => 'form-control',
                        'placeholder' => 'Digite seu e-mail',
                        'required' => true,
                        'label' => false,
                        'div' => false,
                        'maxlength' => 100
                    )); ?>
                </div>
                
                <div class="form-group">
                    <?php echo $this->Form->label('password', 'Senha *', array('class' => 'form-label')); ?>
                    <?php echo $this->Form->input('password', array(
                        'type' => 'password',
                        'class' => 'form-control',
                        'placeholder' => 'Digite uma senha segura',
                        'required' => true,
                        'label' => false,
                        'div' => false,
                        'minlength' => 6
                    )); ?>
                    <small class="form-text text-muted">
                        Mínimo 6 caracteres.
                    </small>
                </div>
                
                <div class="form-group">
                    <?php echo $this->Form->label('password_confirm', 'Confirmar Senha *', array('class' => 'form-label')); ?>
                    <?php echo $this->Form->input('password_confirm', array(
                        'type' => 'password',
                        'class' => 'form-control',
                        'placeholder' => 'Digite a senha novamente',
                        'required' => true,
                        'label' => false,
                        'div' => false,
                        'minlength' => 6
                    )); ?>
                </div>
                
                <?php if (isset($currentUser) && !empty($currentUser) && $currentUser['role'] === 'admin'): ?>
                    <div class="form-group">
                        <?php echo $this->Form->label('role', 'Perfil', array('class' => 'form-label')); ?>
                        <?php echo $this->Form->input('role', array(
                            'type' => 'select',
                            'options' => array(
                                'user' => 'Usuário (pode criar e gerenciar seus próprios posts)',
                                'admin' => 'Administrador (acesso total ao sistema)'
                            ),
                            'class' => 'form-control',
                            'label' => false,
                            'div' => false,
                            'default' => 'user'
                        )); ?>
                        <small class="form-text text-muted">
                            Apenas administradores podem criar outros administradores.
                        </small>
                    </div>
                <?php endif; ?>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <?php if (isset($currentUser) && !empty($currentUser)): ?>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-arrow-left"></i> Cancelar', 
                            array('action' => 'index'),
                            array('class' => 'btn btn-secondary', 'escape' => false)
                        ); ?>
                    <?php else: ?>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-arrow-left"></i> Voltar ao Login', 
                            '/login',
                            array('class' => 'btn btn-secondary', 'escape' => false)
                        ); ?>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-check"></i> 
                        <?php echo isset($currentUser) && !empty($currentUser) ? 'Adicionar Usuário' : 'Cadastrar-se'; ?>
                    </button>
                </div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        
        <?php if (!isset($currentUser) || empty($currentUser)): ?>
            <!-- Info for new users -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle text-info"></i> Por que se cadastrar?</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 small text-muted">
                        <li>Crie e publique seus próprios posts</li>
                        <li>Gerencie seu conteúdo (editar, excluir)</li>
                        <li>Dashboard personalizado com estatísticas</li>
                        <li>Controle de privacidade (rascunhos)</li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validação de confirmação de senha
    const form = document.querySelector('form');
    const password = document.getElementById('UserPassword');
    const passwordConfirm = document.getElementById('UserPasswordConfirm');
    
    function validatePasswords() {
        if (password.value !== passwordConfirm.value) {
            passwordConfirm.setCustomValidity('As senhas não coincidem');
        } else {
            passwordConfirm.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePasswords);
    passwordConfirm.addEventListener('input', validatePasswords);
    
    // Validação do form
    form.addEventListener('submit', function(e) {
        const username = document.getElementById('UserUsername').value.trim();
        const email = document.getElementById('UserEmail').value.trim();
        const pass = password.value.trim();
        const passConfirm = passwordConfirm.value.trim();
        
        if (username === '' || email === '' || pass === '' || passConfirm === '') {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios.');
            return false;
        }
        
        if (username.length < 3) {
            e.preventDefault();
            alert('O nome de usuário deve ter pelo menos 3 caracteres.');
            return false;
        }
        
        if (pass.length < 6) {
            e.preventDefault();
            alert('A senha deve ter pelo menos 6 caracteres.');
            return false;
        }
        
        if (pass !== passConfirm) {
            e.preventDefault();
            alert('As senhas não coincidem.');
            return false;
        }
        
        // Validação simples de email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Por favor, digite um e-mail válido.');
            return false;
        }
    });
});
</script>