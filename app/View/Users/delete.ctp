<div class="p-4">
    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            <h3 class="mb-0">Confirmar Exclusão de Conta</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <strong>⚠️ Atenção!</strong> Esta ação não pode ser desfeita.
            </div>
            
            <p><strong>Usuário:</strong> <?= h($user['User']['username']) ?></p>
            <p><strong>Perfil:</strong> <?= h($user['User']['role']) ?></p>
            <p class="mt-3">Ao confirmar, sua conta e todos os dados associados serão permanentemente excluídos.</p>
            
            <?php echo $this->Form->create('User', [
                'url' => ['action' => 'delete']
            ]); ?>
            
            <div class="d-flex gap-2 mt-4">
                <?php echo $this->Form->button('Sim, Excluir Minha Conta', [
                    'type' => 'submit',
                    'class' => 'btn btn-danger'
                ]); ?>
                
                <?php echo $this->Html->link('Cancelar', [
                    'action' => 'profile'
                ], [
                    'class' => 'btn btn-secondary'
                ]); ?>
            </div>
            
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
