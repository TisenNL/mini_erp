<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">Dados do Produto</h5>
    </div>
    <div class="card-body">
        <?php echo form_open('produtos/create'); ?>
            <div class="form-group">
                <label for="nome">Nome do Produto *</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="preco">Preço (R$) *</label>
                <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="quantidade">Quantidade em Estoque *</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" min="0" required>
            </div>
            
            <hr>
            
            <h5>Variações do Produto</h5>
            <p class="text-muted">Se o produto não possui variações, deixe os campos abaixo em branco.</p>
            
            <div id="variacoes-container">
                <div class="row mb-3 variacao-item">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="variacoes[]" placeholder="Nome da Variação">
                    </div>
                    <div class="col-md-5">
                        <input type="number" class="form-control" name="estoques_variacao[]" placeholder="Quantidade" min="0">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remover-variacao">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <button type="button" class="btn btn-outline-primary mb-4" id="adicionar-variacao">
                <i class="fas fa-plus"></i> Adicionar Variação
            </button>
            
            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Salvar Produto
                </button>
                <a href="<?php echo base_url('produtos'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    jQueryReady(function($) {
        // Adicionar nova variação
        $('#adicionar-variacao').click(function() {
            const html = `
                <div class="row mb-3 variacao-item">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="variacoes[]" placeholder="Nome da Variação">
                    </div>
                    <div class="col-md-5">
                        <input type="number" class="form-control" name="estoques_variacao[]" placeholder="Quantidade" min="0">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remover-variacao">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            $('#variacoes-container').append(html);
        });
        
        // Remover variação
        $(document).on('click', '.remover-variacao', function() {
            $(this).closest('.variacao-item').remove();
        });
    });
</script>