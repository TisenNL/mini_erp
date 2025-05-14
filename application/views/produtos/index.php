<div class="mb-4">
    <a href="<?php echo base_url('produtos/create'); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Adicionar Novo Produto
    </a>
</div>

<?php if (empty($produtos)): ?>
    <div class="alert alert-info">
        Nenhum produto cadastrado. Clique no botão acima para adicionar o primeiro produto.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($produtos as $produto): ?>
            <div class="col-md-4 mb-4">
                <div class="card produto-card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $produto->nome; ?></h5>
                        <p class="card-text">
                            <strong>Preço:</strong> R$ <?php echo number_format($produto->preco, 2, ',', '.'); ?>
                        </p>
                        <p class="card-text">
                            <strong>Estoque:</strong> 
                            <?php if (isset($produto->quantidade)): ?>
                                <?php echo $produto->quantidade; ?> unidades
                            <?php else: ?>
                                <span class="text-danger">Não disponível</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row">
                            <div class="col-6">
                                <a href="<?php echo base_url('produtos/view/' . $produto->id); ?>" class="btn btn-info btn-sm btn-block">
                                    <i class="fas fa-eye"></i> Detalhes
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?php echo base_url('produtos/edit/' . $produto->id); ?>" class="btn btn-primary btn-sm btn-block">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </div>
                        </div>
                        <div class="mt-2">
                            <a href="<?php echo base_url('produtos/comprar/' . $produto->id); ?>" class="btn btn-success btn-block">
                                <i class="fas fa-shopping-cart"></i> Comprar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>