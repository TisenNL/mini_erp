<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">Editar Produto</h5>
    </div>
    <div class="card-body">
        <?php echo form_open('produtos/edit/' . $produto->id); ?>
            <div class="form-group">
                <label for="nome">Nome do Produto *</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $produto->nome; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="preco">Preço (R$) *</label>
                <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="0" value="<?php echo $produto->preco; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="quantidade">Quantidade em Estoque *</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" min="0" value="<?php echo $produto->estoque->quantidade; ?>" required>
                <input type="hidden" name="estoque_id" value="<?php echo $produto->estoque->id; ?>">
            </div>
            
            <hr>
            
            <h5>Variações do Produto</h5>
            
            <div id="variacoes-existentes-container">
                <?php if (!empty($produto->variacoes)): ?>
                    <?php foreach ($produto->variacoes as $variacao): ?>
                        <div class="row mb-3 variacao-item">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="nomes_variacao[]" value="<?php echo $variacao->nome; ?>" placeholder="Nome da Variação">
                                <input type="hidden" name="variacao_ids[]" value="<?php echo $variacao->id; ?>">
                            </div>
                            <div class="col-md-5">
                                <input type="number" class="form-control" name="quantidades_variacao[]" value="<?php echo $variacao->estoque->quantidade; ?>" placeholder="Quantidade" min="0">
                                <input type="hidden" name="estoques_variacao_ids[]" value="<?php echo $variacao->estoque->id; ?>">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remover-variacao">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <h6 class="mt-4">Adicionar Novas Variações</h6>
            
            <div id="novas-variacoes-container">
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
                    <i class="fas fa-save"></i> Salvar Alterações
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
            
            $('#novas-variacoes-container').append(html);
        });
        
        // Remover variação
        $(document).on('click', '.remover-variacao', function() {
            $(this).closest('.variacao-item').remove();
        });
    });
</script>