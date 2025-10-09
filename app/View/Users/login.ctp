<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Login</h4>
                </div>
                <div class="card-body">
                    <?php echo $this->Session->flash(); ?>

                    <?php echo $this->Form->create('User', array('class' => 'needs-validation', 'novalidate' => true)); ?>

                    <div class="mb-3">
                        <?php echo $this->Form->input('username', array(
                            'required' => true,
                            'label' => array('text' => 'Usuário', 'class' => 'form-label'),
                            'div' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Digite seu usuário'
                        )); ?>
                    </div>

                    <div class="mb-3">
                        <?php echo $this->Form->input('password', array(
                            'required' => true,
                            'label' => array('text' => 'Senha', 'class' => 'form-label'),
                            'div' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Digite sua senha'
                        )); ?>
                    </div>

                    <div class="d-grid">
                        <?php echo $this->Form->end(array(
                            'label' => 'Entrar',
                            'class' => 'btn btn-primary btn-lg'
                        )); ?>
                    </div>
                </div>
                <div class="card-footer text-center d-flex flex-column gap-2">
                    <small>
                        Quer navegar sem conta?
                        <?php echo $this->Html->link('Continue como visitante', array('controller' => 'Posts', 'action' => 'index'), array('class' => 'text-decoration-none')); ?>
                    </small>
                    <small>
                        Não tem conta?
                        <?php echo $this->Html->link('Registre-se', array('action' => 'register'), array('class' => 'text-decoration-none')); ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>