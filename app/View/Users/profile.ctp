<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Meu Perfil</h2>
    <?= $this->Html->link('Editar Conta', ['action' => 'edit'], ['class' => 'btn btn-dark']) ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>Nome de Usuário:</strong>
                <p class="text-muted"><?php echo h($user['User']['username']); ?></p>
            </div>

            <div class="col-md-6 mb-3">
                <strong>Perfil:</strong>
                <p>
                    <span class="badge bg-<?= $user['User']['role'] === 'admin' ? 'danger' : 'success' ?>">
                        <?php echo h($user['User']['role']); ?>
                    </span>
                </p>
            </div>

            <div class="col-md-6 mb-3">
                <strong>Conta criada em:</strong>
                <p class="text-muted"><?php echo $this->Time->format('d/m/Y H:i', $user['User']['created']); ?></p>
            </div>

            <div class="col-md-6 mb-3">
                <strong>Última modificação:</strong>
                <p class="text-muted"><?php echo $this->Time->format('d/m/Y H:i', $user['User']['modified']); ?></p>
            </div>
        </div>
    </div>
</div>