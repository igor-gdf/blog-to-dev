/**
 * Blog-to-Dev - Scripts com jQuery
 */

$(document).ready(function() {
    
    // Adiciona classe de cor baseado no status do post
    function setClassColor($element, status) {
        if (status === 'published') {
            $element.addClass('text-success');
        } else if (status === 'draft') {
            $element.addClass('text-warning');
        } else {
            $element.addClass('text-black');
        }
    }

    // Aplica cores nos status dos posts
    $('.card-footer small:nth-child(2)').each(function() {
        var status = $(this).text().trim().toLowerCase();
        setClassColor($(this), status);
    });

    // Handler dos botões de status nos formulários de posts
    $('button[data-status]').on('click', function(e) {
        var status = $(this).data('status');
        $('#post-status, input[name="data[Post][status]"]').val(status);
    });

    // Confirmação de exclusão personalizada
    $('a[data-confirm], button[data-confirm]').on('click', function(e) {
        var message = $(this).data('confirm');
        if (message && !confirm(message)) {
            e.preventDefault();
            return false;
        }
    });

    // Auto-hide para mensagens flash após 5 segundos
    function autoHideAlerts() {
        $('.alert.alert-dismissible').each(function() {
            var $alert = $(this);
            if (!$alert.data('auto-hide-set')) {
                $alert.data('auto-hide-set', true);
                setTimeout(function() {
                    if ($alert.length && $alert.is(':visible')) {
                        $alert.fadeOut(400, function() {
                            $alert.remove();
                        });
                    }
                }, 5000);
            }
        });
    }
    
    // Executa imediatamente e monitora novos alerts
    autoHideAlerts();
    
    // Observa mudanças no DOM para novos alerts
    var observer = new MutationObserver(function(mutations) {
        autoHideAlerts();
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Validação de formulários
    $('form.needs-validation').on('submit', function(e) {
        if (this.checkValidity() === false) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // Tooltip Bootstrap
    $('[data-bs-toggle="tooltip"]').each(function() {
        new bootstrap.Tooltip(this);
    });

    // Popover Bootstrap
    $('[data-bs-toggle="popover"]').each(function() {
        new bootstrap.Popover(this);
    });
});
