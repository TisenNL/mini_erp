</div><!-- /.container -->

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p>&copy; <?php echo date('Y'); ?> Mini ERP - Todos os direitos reservados</p>
            </div>
            <div class="col-md-6 text-right">
                <p>Sistema de Controle de Pedidos, Produtos, Cupons e Estoque</p>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery e Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Executar todos os scripts que dependem do jQuery
    $(document).ready(function() {
        // Executar scripts em espera
        if (App.ready && App.ready.length) {
            App.ready.forEach(function(fn) {
                fn(jQuery);
            });
        }
        
        // Auto-close alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>

</body>
</html>