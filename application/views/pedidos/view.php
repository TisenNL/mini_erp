<div class="mb-4">
    <a href="<?php echo base_url('pedidos'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar para Pedidos
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Detalhes do Pedido #<?php echo $pedido->id; ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informações do Cliente</h6>
                        <p>
                            <strong>Nome:</strong> <?php echo $pedido->cliente_nome; ?><br>
                            <strong>E-mail:</strong> <?php echo $pedido->cliente_email; ?><br>
                            <strong>Telefone:</strong> <?php echo $pedido->cliente_telefone; ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Endereço de Entrega</h6>
                        <p>
                            <?php echo $pedido->endereco; ?>, <?php echo $pedido->numero; ?>
                            <?php if ($pedido->complemento): ?>
                                - <?php echo $pedido->complemento; ?>
                            <?php endif; ?>
                            <br>
                            <?php echo $pedido->bairro; ?><br>
                            <?php echo $pedido->cidade; ?> - <?php echo $pedido->estado; ?><br>
                            CEP: <?php echo $pedido->cep; ?>
                        </p>
                    </div>
                </div>
                
                <h6 class="mt-4">Itens do Pedido</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th class="text-center">Quantidade</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($itens as $item): ?>
                                <tr>
                                    <td>
                                        <?php echo $item->produto_nome; ?>
                                        <?php if ($item->variacao_nome): ?>
                                            <br>
                                            <small class="text-muted">Variação: <?php echo $item->variacao_nome; ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>R$ <?php echo number_format($item->preco_unitario, 2, ',', '.'); ?></td>
                                    <td class="text-center"><?php echo $item->quantidade; ?></td>
                                    <td class="text-right">R$ <?php echo number_format($item->subtotal, 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
                    <strong>R$ <?php echo number_format($pedido->subtotal, 2, ',', '.'); ?></strong>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Frete:</span>
                    <strong>
                        <?php if ($pedido->valor_frete > 0): ?>
                            R$ <?php echo number_format($pedido->valor_frete, 2, ',', '.'); ?>
                        <?php else: ?>
                            <span class="text-success">Grátis</span>
                        <?php endif; ?>
                    </strong>
                </div>
                
                <?php if ($pedido->desconto > 0): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Desconto:</span>
                        <strong class="text-success">- R$ <?php echo number_format($pedido->desconto, 2, ',', '.'); ?></strong>
                    </div>
                <?php endif; ?>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <span>Total:</span>
                    <strong class="h4 text-success">R$ <?php echo number_format($pedido->valor_total, 2, ',', '.'); ?></strong>
                </div>
                
                <div class="alert alert-info mt-3">
                    <small>
                        <i class="fas fa-info-circle"></i>
                        Status do Pedido: <strong><?php echo ucfirst($pedido->status); ?></strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>