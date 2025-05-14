<?php if (empty($carrinho)): ?>
    <div class="alert alert-info">
        Seu carrinho está vazio. <a href="<?php echo base_url('produtos'); ?>">Clique aqui</a> para adicionar produtos.
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Itens no Carrinho</h5>
                </div>
                <div class="card-body">
                    <?php echo form_open('carrinho/atualizar'); ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Preço</th>
                                        <th width="120">Quantidade</th>
                                        <th class="text-right">Subtotal</th>
                                        <th width="80">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($carrinho as $item_key => $item): ?>
                                        <tr>
                                            <td>
                                                <?php echo $item['produto_nome']; ?>
                                                <?php if ($item['variacao_nome']): ?>
                                                    <br>
                                                    <small class="text-muted">Variação: <?php echo $item['variacao_nome']; ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?>
                                            </td>
                                            <td>
                                                <input type="number" name="quantidade[<?php echo $item_key; ?>]" class="form-control form-control-sm" value="<?php echo $item['quantidade']; ?>" min="1">
                                            </td>
                                            <td class="text-right">
                                                R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('carrinho/remover/' . $item_key); ?>" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3">
                            <a href="<?php echo base_url('produtos'); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Continuar Comprando
                            </a>
                            
                            <div>
                                <a href="<?php echo base_url('carrinho/limpar'); ?>" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i> Limpar Carrinho
                                </a>
                                <button type="submit" class="btn btn-outline-success ml-2">
                                    <i class="fas fa-sync"></i> Atualizar Carrinho
                                </button>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Resumo do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></strong>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Frete:</span>
                        <strong>
                            <?php if ($frete > 0): ?>
                                R$ <?php echo number_format($frete, 2, ',', '.'); ?>
                            <?php else: ?>
                                <span class="text-success">Grátis</span>
                            <?php endif; ?>
                        </strong>
                    </div>
                    
                    <?php if ($desconto > 0): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Desconto:</span>
                            <strong class="text-success">- R$ <?php echo number_format($desconto, 2, ',', '.'); ?></strong>
                        </div>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span>Total:</span>
                        <strong class="h5 text-success">R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
                    </div>
                    
                    <?php if ($cupom_id): ?>
                        <div class="alert alert-success">
                            <small>
                                <i class="fas fa-check-circle"></i>
                                Cupom <strong><?php echo $cupom->codigo; ?></strong> aplicado com sucesso!
                                <a href="<?php echo base_url('carrinho/remover_cupom'); ?>" class="text-danger float-right">
                                    <i class="fas fa-times"></i> Remover
                                </a>
                            </small>
                        </div>
                    <?php else: ?>
                        <div class="text-center mb-3">
                            <p class="mb-1"><small>Possui um cupom de desconto?</small></p>
                            <?php echo form_open('carrinho/aplicar_cupom'); ?>
                                <div class="input-group">
                                    <input type="text" name="cupom" class="form-control" placeholder="Digite o código">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-tag"></i> Aplicar
                                        </button>
                                    </div>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <a href="<?php echo base_url('carrinho/checkout'); ?>" class="btn btn-success btn-block">
                        <i class="fas fa-check"></i> Finalizar Compra
                    </a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Cálculo do Frete</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><small>Digite seu CEP para calcular o frete:</small></p>
                    <div class="input-group">
                        <input type="text" id="cep" class="form-control" placeholder="00000-000">
                        <div class="input-group-append">
                            <button type="button" id="calcular-frete" class="btn btn-outline-info">
                                <i class="fas fa-map-marker-alt"></i> Calcular
                            </button>
                        </div>
                    </div>
                    
                    <div id="resultado-cep" class="mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        jQueryReady(function($) {
            // Máscara para CEP
            $('#cep').on('input', function() {
                let v = $(this).val();
                v = v.replace(/\D/g, '');
                v = v.replace(/^(\d{5})(\d)/, '$1-$2');
                $(this).val(v);
            });
            
            // Calcular frete via CEP
            $('#calcular-frete').click(function() {
                const cep = $('#cep').val().replace(/\D/g, '');
                
                if (cep.length !== 8) {
                    $('#resultado-cep').html('<div class="alert alert-danger">CEP inválido!</div>').show();
                    return;
                }
                
                $('#resultado-cep').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>').show();
                
                $.ajax({
                    url: '<?php echo base_url('carrinho/buscar_cep'); ?>',
                    type: 'POST',
                    data: { cep: cep },
                    dataType: 'json',
                    success: function(response) {
                        if (response.erro) {
                            $('#resultado-cep').html('<div class="alert alert-danger">CEP não encontrado!</div>');
                        } else {
                            let html = '<div class="alert alert-success">';
                            html += `<strong>Endereço:</strong><br>`;
                            html += `${response.logradouro}<br>`;
                            html += `${response.bairro}, ${response.localidade} - ${response.uf}`;
                            html += '</div>';
                            
                            $('#resultado-cep').html(html);
                        }
                    },
                    error: function() {
                        $('#resultado-cep').html('<div class="alert alert-danger">Erro ao buscar CEP!</div>');
                    }
                });
            });
        });
    </script>
<?php endif; ?>