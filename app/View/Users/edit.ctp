<?php 
$isOwnProfile = $currentUser['id'] == $user['User']['id'];
$title = $isOwnProfile ? 'Editar Meu Perfil' : 'Editar Usuário: ' . h($user['User']['username']);
$this->set('title_for_layout', $title);
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-user-edit text-primary"></i> 
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
                    <?php echo $this->Form->label('password', 'Nova Senha', array('class' => 'form-label')); ?>
                    <?php echo $this->Form->input('password', array(
                        'type' => 'password',
                        'class' => 'form-control',
                        'placeholder' => 'Digite uma nova senha (deixe em branco para manter)',
                        'label' => false,
                        'div' => false,
                        'minlength' => 6
                    )); ?>
                    <small class="form-text text-muted">
                        Deixe em branco se não quiser alterar a senha. Mínimo 6 caracteres.
                    </small>
                </div>
                
                <div class="form-group">
                    <?php echo $this->Form->label('password_confirm', 'Confirmar Nova Senha', array('class' => 'form-label')); ?>
                    <?php echo $this->Form->input('password_confirm', array(
                        'type' => 'password',
                        'class' => 'form-control',
                        'placeholder' => 'Digite a nova senha novamente',
                        'label' => false,
                        'div' => false,
                        'minlength' => 6
                    )); ?>
                </div>
                
                <?php if ($currentUser['role'] === 'admin' && !$isOwnProfile): ?>
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
                            'div' => false
                        )); ?>
                        <small class="form-text text-muted">
                            <?php if ($user['User']['role'] === 'admin'): ?>
                                <span class="text-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Cuidado: alterar o perfil de um administrador pode afetar o sistema.
                                </span>
                            <?php else: ?>
                                Apenas administradores podem alterar perfis de usuários.
                            <?php endif; ?>
                        </small>
                    </div>
                <?php elseif ($isOwnProfile && $user['User']['role'] === 'admin'): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-crown"></i>
                        <strong>Administrador:</strong> Você não pode alterar seu próprio perfil de administrador por segurança.
                        <?php echo $this->Form->hidden('role', array('value' => 'admin')); ?>
                    </div>
                <?php else: ?>
                    <?php echo $this->Form->hidden('role'); ?>
                <?php endif; ?>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <div>
                        <?php if ($currentUser['role'] === 'admin' && !$isOwnProfile): ?>
                            <?php echo $this->Html->link(
                                '<i class="fas fa-list"></i> Lista de Usuários', 
                                array('action' => 'index'),
                                array('class' => 'btn btn-secondary', 'escape' => false)
                            ); ?>
                        <?php else: ?>
                            <?php echo $this->Html->link(
                                '<i class="fas fa-user"></i> Ver Perfil', 
                                array('action' => 'view', $user['User']['id']),
                                array('class' => 'btn btn-info', 'escape' => false)
                            ); ?>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 
                        Salvar Alterações
                    </button>
                </div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        
        <!-- Informações Adicionais -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle text-info"></i> 
                    Informações da Conta
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <strong>Cadastrado em:</strong><br>
                            <?php echo $this->Time->format('d/m/Y H:i', $user['User']['created']); ?>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">
                            <strong>Última atualização:</strong><br>
                            <?php echo $this->Time->format('d/m/Y H:i', $user['User']['modified']); ?>
                        </small>
                    </div>
                </div>
                
                <?php if ($isOwnProfile): ?>
                    <hr>
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt text-success"></i>
                            Suas informações estão protegidas e apenas você (e administradores) podem alterá-las.
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Validação apenas se os campos de senha existirem
    if (password && passwordConfirm) {
        password.addEventListener('input', validatePasswords);
        passwordConfirm.addEventListener('input', validatePasswords);
    }
    
    // Validação do form
    form.addEventListener('submit', function(e) {
        const username = document.getElementById('UserUsername').value.trim();
        const email = document.getElementById('UserEmail').value.trim();
        const pass = password ? password.value.trim() : '';
        const passConfirm = passwordConfirm ? passwordConfirm.value.trim() : '';
        
        if (username === '' || email === '') {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios.');
            return false;
        }
        
        if (username.length < 3) {
            e.preventDefault();
            alert('O nome de usuário deve ter pelo menos 3 caracteres.');
            return false;
        }
        
        // Validação de senha apenas se foi preenchida
        if (pass !== '' || passConfirm !== '') {
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