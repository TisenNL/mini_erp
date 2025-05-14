<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-4">
            <div class="card-body">
                <h5 class="card-title">Total de Produtos</h5>
                <h2 class="card-text"><?php echo $total_produtos; ?></h2>
                <a href="<?php echo base_url('produtos'); ?>" class="btn btn-light">Ver Produtos</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-success mb-4">
            <div class="card-body">
                <h5 class="card-title">Total de Pedidos</h5>
                <h2 class="card-text"><?php echo $total_pedidos; ?></h2>
                <a href="#" class="btn btn-light">Ver Pedidos</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-info mb-4">
            <div class="card-body">
                <h5 class="card-title">Ações Rápidas</h5>
                <div class="d-flex">
                    <a href="<?php echo base_url('produtos/create'); ?>" class="btn btn-light mr-2">Novo Produto</a>
                    <a href="<?php echo base_url('cupons/create'); ?>" class="btn btn-light">Novo Cupom</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Produtos Recentes</h5>
            </div>
            <div class="card-body">
                <?php if (empty($produtos)): ?>
                    <p class="text-muted">Nenhum produto cadastrado ainda.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach (array_slice($produtos, 0, 5) as $produto): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?php echo $produto->nome; ?></span>
                                <span class="badge badge-primary badge-pill">R$ <?php echo number_format($produto->preco, 2, ',', '.'); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Pedidos Recentes</h5>
            </div>
            <div class="card-body">
                <?php if (empty($pedidos)): ?>
                    <p class="text-muted">Nenhum pedido realizado ainda.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach (array_slice($pedidos, 0, 5) as $pedido): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Pedido #<?php echo $pedido->id; ?> - <?php echo $pedido->cliente_nome; ?></span>
                                <div>
                                    <span class="badge badge-<?php echo ($pedido->status == 'pendente') ? 'warning' : (($pedido->status == 'cancelado') ? 'danger' : 'success'); ?> mr-2">
                                        <?php echo ucfirst($pedido->status); ?>
                                    </span>
                                    <span class="badge badge-primary badge-pill">R$ <?php echo number_format($pedido->valor_total, 2, ',', '.'); ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="card-title mb-0">Acesso Rápido</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <a href="<?php echo base_url('produtos'); ?>" class="btn btn-outline-primary btn-block">
                    <i class="fas fa-box-open mb-2 d-block" style="font-size: 24px;"></i>
                    Gerenciar Produtos
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="<?php echo base_url('cupons'); ?>" class="btn btn-outline-success btn-block">
                    <i class="fas fa-tags mb-2 d-block" style="font-size: 24px;"></i>
                    Gerenciar Cupons
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="<?php echo base_url('carrinho'); ?>" class="btn btn-outline-info btn-block">
                    <i class="fas fa-shopping-cart mb-2 d-block" style="font-size: 24px;"></i>
                    Ver Carrinho
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="#" class="btn btn-outline-dark btn-block">
                    <i class="fas fa-chart-line mb-2 d-block" style="font-size: 24px;"></i>
                    Relatórios
                </a>
            </div>
        </div>
    </div>
</div>