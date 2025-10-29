<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Registro</h4>
                </div>
                <div class="card-body">
                    <?php echo $this->Session->flash(); ?>

                    <?php echo $this->Form->create('User', array('class' => 'needs-validation', 'novalidate' => true)); ?>

                    <div class="mb-3">
                        <?php echo $this->Form->input('username', array(
                            'required' => true,
                            'label' => 'Usuário',
                            'div' => array('class' => 'form-group'),
                            'class' => 'form-control'
                        )); ?>
                    </div>

                    <div class="mb-3">
                        <?php echo $this->Form->input('password', array(
                            'required' => true,
                            'label' => 'Senha',
                            'div' => array('class' => 'form-group'),
                            'class' => 'form-control'
                        )); ?>
                    </div>

                    <div class="mb-3">
                        <?php echo $this->Form->input('confirm_password', array(
                            'type' => 'password',
                            'required' => true,
                            'label' => 'Confirmar Senha',
                            'div' => array('class' => 'form-group'),
                            'class' => 'form-control'
                        )); ?>
                    </div>

                    <div class="mb-3">
                        <?php echo $this->Form->input('role', array('type' => 'hidden', 'value' => 'author')); ?>
                    </div>

                    <div class="d-grid">
                        <?php echo $this->Form->submit(__('Registrar'), array('class' => 'btn bg-black text-white btn-lg')); ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <small>
                        Já tem conta?
                        <?php echo $this->Html->link('Faça login', array('action' => 'login'), array('class' => 'text-decoration-none')); ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>