<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Dados para Entrega</h5>
            </div>
            <div class="card-body">
                <?php echo form_open('carrinho/checkout'); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cliente_nome">Nome Completo *</label>
                                <input type="text" class="form-control" id="cliente_nome" name="cliente_nome" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cliente_email">E-mail *</label>
                                <input type="email" class="form-control" id="cliente_email" name="cliente_email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cliente_telefone">Telefone *</label>
                                <input type="tel" class="form-control" id="cliente_telefone" name="cliente_telefone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cep">CEP *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cep" name="cep" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary" id="buscar-cep">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="endereco">Endereço *</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="numero">Número *</label>
                                <input type="text" class="form-control" id="numero" name="numero" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="complemento">Complemento</label>
                                <input type="text" class="form-control" id="complemento" name="complemento">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="bairro">Bairro *</label>
                                <input type="text" class="form-control" id="bairro" name="bairro" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="cidade">Cidade *</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="estado">Estado *</label>
                                <select class="form-control" id="estado" name="estado" required>
                                    <option value="">Selecione</option>
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-check"></i> Finalizar Pedido
                        </button>
                        <a href="<?php echo base_url('carrinho'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar para o Carrinho
                        </a>
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
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($carrinho as $item): ?>
                                <tr>
                                    <td>
                                        <?php echo $item['produto_nome']; ?>
                                        <?php if ($item['variacao_nome']): ?>
                                            <br>
                                            <small class="text-muted">Variação: <?php echo $item['variacao_nome']; ?></small>
                                        <?php endif; ?>
                                        <br>
                                        <small class="text-muted"><?php echo $item['quantidade']; ?> x R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></small>
                                    </td>
                                    <td class="text-right">
                                        R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <hr>
                
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
                
                <div class="d-flex justify-content-between">
                    <span>Total:</span>
                    <strong class="h4 text-success">R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
                </div>
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
        
        // Buscar CEP via API
        $('#buscar-cep').click(function() {
            const cep = $('#cep').val().replace(/\D/g, '');
            
            if (cep.length !== 8) {
                alert('CEP inválido!');
                return;
            }
            
            $.ajax({
                url: '<?php echo base_url('carrinho/buscar_cep'); ?>',
                type: 'POST',
                data: { cep: cep },
                dataType: 'json',
                success: function(response) {
                    if (response.erro) {
                        alert('CEP não encontrado!');
                    } else {
                        $('#endereco').val(response.logradouro);
                        $('#bairro').val(response.bairro);
                        $('#cidade').val(response.localidade);
                        $('#estado').val(response.uf);
                        $('#numero').focus();
                    }
                },
                error: function() {
                    alert('Erro ao buscar CEP!');
                }
            });
        });
        
        // Máscara para telefone
        $('#cliente_telefone').on('input', function() {
            let v = $(this).val();
            v = v.replace(/\D/g, '');
            v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
            v = v.replace(/(\d)(\d{4})$/, '$1-$2');
            $(this).val(v);
        });
    });
</script>