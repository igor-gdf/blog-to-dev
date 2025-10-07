<?php
$this->set('title_for_layout', 'Criar Novo Post - Blog CakePHP');
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-plus-circle text-success"></i> 
                    Criar Novo Post
                </h4>
            </div>
            <div class="card-body">
                <?php echo $this->Form->create('Post', array(
                    'class' => 'needs-validation',
                    'novalidate' => true
                )); ?>
                
                <div class="form-group">
                    <?php echo $this->Form->label('title', 'Título *', array('class' => 'form-label')); ?>
                    <?php echo $this->Form->input('title', array(
                        'type' => 'text',
                        'class' => 'form-control',
                        'placeholder' => 'Digite um título atrativo para seu post...',
                        'required' => true,
                        'label' => false,
                        'div' => false,
                        'maxlength' => 200
                    )); ?>
                    <small class="form-text text-muted">
                        <span id="title-counter">0</span>/200 caracteres
                    </small>
                </div>
                
                <div class="form-group">
                    <?php echo $this->Form->label('content', 'Conteúdo *', array('class' => 'form-label')); ?>
                    <?php echo $this->Form->input('content', array(
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'rows' => 10,
                        'placeholder' => 'Escreva o conteúdo do seu post aqui...',
                        'required' => true,
                        'label' => false,
                        'div' => false
                    )); ?>
                    <small class="form-text text-muted">
                        <span id="content-counter">0</span> caracteres
                    </small>
                </div>
                
                <div class="form-group">
                    <?php echo $this->Form->label('status', 'Status', array('class' => 'form-label')); ?>
                    <?php echo $this->Form->input('status', array(
                        'type' => 'select',
                        'options' => array(
                            'draft' => 'Rascunho (não visível publicamente)',
                            'published' => 'Publicado (visível para todos)'
                        ),
                        'class' => 'form-control',
                        'label' => false,
                        'div' => false,
                        'default' => 'draft'
                    )); ?>
                    <small class="form-text text-muted">
                        Escolha "Rascunho" para salvar sem publicar ou "Publicado" para disponibilizar no blog.
                    </small>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <?php echo $this->Html->link(
                        '<i class="fas fa-arrow-left"></i> Cancelar', 
                        array('action' => 'index'),
                        array('class' => 'btn btn-secondary', 'escape' => false)
                    ); ?>
                    
                    <div>
                        <button type="submit" name="status" value="draft" class="btn btn-warning mr-2">
                            <i class="fas fa-save"></i> Salvar como Rascunho
                        </button>
                        <button type="submit" name="status" value="published" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Publicar Post
                        </button>
                    </div>
                </div>
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        
        <!-- Dicas -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-lightbulb text-warning"></i> Dicas para um bom post</h6>
            </div>
            <div class="card-body">
                <ul class="mb-0 small text-muted">
                    <li>Use um título claro e atrativo</li>
                    <li>Organize o conteúdo em parágrafos</li>
                    <li>Revise antes de publicar</li>
                    <li>Use "Rascunho" para salvar enquanto escreve</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script>
$(document).ready(function() {
    // Contador de caracteres para título
    $('#PostTitle').on('input', function() {
        var length = $(this).val().length;
        $('#title-counter').text(length);
        
        if (length > 200) {
            $('#title-counter').addClass('text-danger');
        } else if (length > 180) {
            $('#title-counter').addClass('text-warning').removeClass('text-danger');
        } else {
            $('#title-counter').removeClass('text-warning text-danger');
        }
    });
    
    // Contador de caracteres para conteúdo
    $('#PostContent').on('input', function() {
        var length = $(this).val().length;
        $('#content-counter').text(length);
    });
    
    // Trigger inicial para contar caracteres existentes
    $('#PostTitle').trigger('input');
    $('#PostContent').trigger('input');
    
    // Validação do form
    $('form').on('submit', function(e) {
        var title = $('#PostTitle').val().trim();
        var content = $('#PostContent').val().trim();
        
        if (title === '' || content === '') {
            e.preventDefault();
            alert('Por favor, preencha o título e o conteúdo do post.');
            return false;
        }
        
        if (title.length > 200) {
            e.preventDefault();
            alert('O título deve ter no máximo 200 caracteres.');
            return false;
        }
    });
});
</script>
<?php $this->end(); ?>