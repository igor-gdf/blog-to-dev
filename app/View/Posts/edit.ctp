<?php
$this->assign('title', 'Editar Post');
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- Header da página -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-edit text-primary"></i> Editar Post</h1>
            <?php echo $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Voltar',
                array('action' => 'index'),
                array('class' => 'btn btn-secondary', 'escape' => false)
            ); ?>
        </div>

        <!-- Formulário de edição -->
        <div class="card">
            <div class="card-body">
                <?php echo $this->Form->create('Post', array(
                    'role' => 'form',
                    'inputDefaults' => array(
                        'label' => false,
                        'div' => false
                    )
                )); ?>

                <div class="form-group mb-3">
                    <label for="PostTitle" class="form-label">
                        <i class="fas fa-heading"></i> Título *
                    </label>
                    <?php echo $this->Form->input('title', array(
                        'type' => 'text',
                        'class' => 'form-control',
                        'placeholder' => 'Digite o título do post...',
                        'required' => true,
                        'maxlength' => 255
                    )); ?>
                    <div class="form-text">Máximo 255 caracteres</div>
                </div>

                <div class="form-group mb-3">
                    <label for="PostContent" class="form-label">
                        <i class="fas fa-file-alt"></i> Conteúdo *
                    </label>
                    <?php echo $this->Form->input('content', array(
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'placeholder' => 'Escreva o conteúdo do post...',
                        'rows' => 10,
                        'required' => true
                    )); ?>
                    <div class="form-text">Use texto simples ou HTML básico</div>
                </div>

                <div class="form-group mb-4">
                    <label for="PostStatus" class="form-label">
                        <i class="fas fa-eye"></i> Status
                    </label>
                    <?php echo $this->Form->input('status', array(
                        'type' => 'select',
                        'options' => array(
                            'draft' => 'Rascunho',
                            'published' => 'Publicado'
                        ),
                        'class' => 'form-control'
                    )); ?>
                    <div class="form-text">
                        <strong>Rascunho:</strong> Visível apenas para você<br>
                        <strong>Publicado:</strong> Visível para todos os visitantes
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="d-flex gap-2 justify-content-between">
                    <div>
                        <?php echo $this->Html->link(
                            '<i class="fas fa-times"></i> Cancelar',
                            array('action' => 'index'),
                            array('class' => 'btn btn-outline-secondary', 'escape' => false)
                        ); ?>
                    </div>
                    <div>
                        <button type="submit" name="status" value="draft" class="btn btn-outline-primary">
                            <i class="fas fa-save"></i> Salvar como Rascunho
                        </button>
                        <button type="submit" name="status" value="published" class="btn btn-success">
                            <i class="fas fa-share"></i> Salvar e Publicar
                        </button>
                    </div>
                </div>

                <?php echo $this->Form->end(); ?>
            </div>
        </div>

        <!-- Informações do post -->
        <?php if (!empty($this->data['Post']['created'])): ?>
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-info-circle"></i> Informações do Post</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-calendar-plus"></i> 
                                Criado em: <?php echo $this->Time->format('d/m/Y H:i', $this->data['Post']['created']); ?>
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-calendar-edit"></i> 
                                Modificado em: <?php echo $this->Time->format('d/m/Y H:i', $this->data['Post']['modified']); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Script para confirmação antes de sair -->
<script>
let formChanged = false;

$(document).ready(function() {
    // Marca como alterado quando campos são modificados
    $('input, textarea, select').on('change input', function() {
        formChanged = true;
    });
    
    // Remove marcação quando formulário é submetido
    $('form').on('submit', function() {
        formChanged = false;
    });
    
    // Avisa antes de sair se há alterações não salvas
    $(window).on('beforeunload', function() {
        if (formChanged) {
            return 'Você tem alterações não salvas. Deseja realmente sair?';
        }
    });
    
    // Contador de caracteres para o título
    $('#PostTitle').on('input', function() {
        const length = $(this).val().length;
        const max = $(this).attr('maxlength');
        const remaining = max - length;
        
        let color = 'text-muted';
        if (remaining < 50) color = 'text-warning';
        if (remaining < 20) color = 'text-danger';
        
        $(this).next('.form-text').html(`${remaining} caracteres restantes`).removeClass().addClass(`form-text ${color}`);
    });
});
</script>