<div class="alert alert-success position-fixed bottom-0 end-0 m-3">
    <?= h($message) ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertNode = document.querySelector('.alert');
    if (alertNode) {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
            bsAlert.close(); // fecha automaticamente após X ms
        }, 5000); // 5000ms = 5 segundos
    }
});
</script>