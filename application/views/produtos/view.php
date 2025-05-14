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
                
                <?php if (!empty($produto->variacoes)): ?>
                    <hr>
                    <h5>Variações Disponíveis</h5>
                    <div class="list-group">
                        <?php foreach ($produto->variacoes as $variacao): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?php echo $variacao->nome; ?></h6>
                                        <small>
                                            Estoque: 
                                            <?php if (isset($variacao->estoque->quantidade)): ?>
                                                <?php echo $variacao->estoque->quantidade; ?> unidades
                                            <?php else: ?>
                                                <span class="text-danger">Não disponível</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <?php if (isset($variacao->estoque->quantidade) && $variacao->estoque->quantidade > 0): ?>
                                        <a href="<?php echo base_url('produtos/comprar/' . $produto->id . '/' . $variacao->id); ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-shopping-cart"></i> Comprar
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-times"></i> Indisponível
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-4">
                        <a href="<?php echo base_url('produtos'); ?>" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo base_url('produtos/edit/' . $produto->id); ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                    <div class="col-md-4">
                        <?php if (isset($produto->estoque->quantidade) && $produto->estoque->quantidade > 0): ?>
                            <a href="<?php echo base_url('produtos/comprar/' . $produto->id); ?>" class="btn btn-success btn-block">
                                <i class="fas fa-shopping-cart"></i> Comprar
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-block" disabled>
                                <i class="fas fa-times"></i> Indisponível
                            </button>
                        <?php endif; ?>
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
                <?php if (isset($produto->estoque->quantidade) && $produto->estoque->quantidade > 0): ?>
                    <a href="<?php echo base_url('produtos/comprar/' . $produto->id); ?>" class="btn btn-outline-success btn-block">
                        <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-block" disabled>
                        <i class="fas fa-times"></i> Produto Indisponível
                    </button>
                <?php endif; ?>
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