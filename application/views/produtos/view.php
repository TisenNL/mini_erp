<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Informações do Produto</h5>
            </div>
            <div class="card-body">
                <h3><?php echo $produto->nome; ?></h3>
                <p class="lead">
                    <strong>Preço:</strong> 
                    R$ <?php echo number_format($produto->preco, 2, ',', '.'); ?>
                </p>
                <p>
                    <strong>Estoque:</strong> 
                    <?php if (isset($produto->estoque->quantidade)): ?>
                        <?php echo $produto->estoque->quantidade; ?> unidades
                    <?php else: ?>
                        <span class="text-danger">Não disponível</span>
                    <?php endif; ?>
                </p>
                
                <?php echo form_open('produtos/comprar/' . $produto->id); ?>
                    <?php if (!empty($produto->variacoes)): ?>
                        <div class="form-group">
                            <label for="variacao"><strong>Selecione uma variação:</strong></label>
                            <select name="variacao_id" id="variacao" class="form-control">
                                <option value="">Produto padrão 
                                    <?php if (isset($produto->estoque->quantidade)): ?>
                                        (<?php echo $produto->estoque->quantidade; ?> em estoque)
                                    <?php else: ?>
                                        (Indisponível)
                                    <?php endif; ?>
                                </option>
                                
                                <?php foreach ($produto->variacoes as $variacao): ?>
                                    <option value="<?php echo $variacao->id; ?>" 
                                        <?php echo (isset($variacao->estoque->quantidade) && $variacao->estoque->quantidade > 0) ? '' : 'disabled'; ?>>
                                        <?php echo $variacao->nome; ?> 
                                        <?php if (isset($variacao->estoque->quantidade)): ?>
                                            (<?php echo $variacao->estoque->quantidade; ?> em estoque)
                                        <?php else: ?>
                                            (Indisponível)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="quantidade"><strong>Quantidade:</strong></label>
                        <input type="number" name="quantidade" id="quantidade" class="form-control" value="1" min="1" max="<?php echo isset($produto->estoque->quantidade) ? $produto->estoque->quantidade : 0; ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-block" <?php echo (isset($produto->estoque->quantidade) && $produto->estoque->quantidade > 0) ? '' : 'disabled'; ?>>
                        <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                    </button>
                <?php echo form_close(); ?>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?php echo base_url('produtos'); ?>" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo base_url('produtos/edit/' . $produto->id); ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Ações Rápidas</h5>
            </div>
            <div class="card-body">
                <a href="<?php echo base_url('produtos/edit/' . $produto->id); ?>" class="btn btn-outline-primary btn-block mb-2">
                    <i class="fas fa-edit"></i> Editar Produto
                </a>
                <a href="#" class="btn btn-outline-danger btn-block mb-2" data-toggle="modal" data-target="#deleteModal">
                    <i class="fas fa-trash"></i> Excluir Produto
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o produto <strong><?php echo $produto->nome; ?></strong>?</p>
                <p class="text-danger">Esta ação não pode ser desfeita!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <a href="<?php echo base_url('produtos/delete/' . $produto->id); ?>" class="btn btn-danger">
                    Sim, Excluir
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    jQueryReady(function($) {
        // Armazenar os dados de estoque das variações
        var estoques = {
            '': <?php echo isset($produto->estoque->quantidade) ? $produto->estoque->quantidade : 0; ?>,
            <?php foreach ($produto->variacoes as $variacao): ?>
            '<?php echo $variacao->id; ?>': <?php echo isset($variacao->estoque->quantidade) ? $variacao->estoque->quantidade : 0; ?>,
            <?php endforeach; ?>
        };
        
        // Atualizar o máximo do campo quantidade com base na variação selecionada
        $('#variacao').change(function() {
            var variacao_id = $(this).val();
            var estoque = estoques[variacao_id] || 0;
            
            // Atualizar máximo e valor atual se necessário
            $('#quantidade').attr('max', estoque);
            
            if (parseInt($('#quantidade').val()) > estoque) {
                $('#quantidade').val(estoque);
            }
            
            // Desabilitar o botão se não houver estoque
            if (estoque <= 0) {
                $('button[type="submit"]').prop('disabled', true);
            } else {
                $('button[type="submit"]').prop('disabled', false);
            }
        });
    });
</script>