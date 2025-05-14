<div class="mb-4">
    <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar para o Dashboard
    </a>
</div>

<?php if (empty($pedidos)): ?>
    <div class="alert alert-info">
        Nenhum pedido registrado ainda.
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?php echo $pedido->id; ?></td>
                                <td>
                                    <?php echo $pedido->cliente_nome; ?><br>
                                    <small class="text-muted"><?php echo $pedido->cliente_email; ?></small>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pedido->created_at)); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo ($pedido->status == 'pendente') ? 'warning' : (($pedido->status == 'cancelado') ? 'danger' : 'success'); ?>">
                                        <?php echo ucfirst($pedido->status); ?>
                                    </span>
                                </td>
                                <td>R$ <?php echo number_format($pedido->valor_total, 2, ',', '.'); ?></td>
                                <td>
                                    <a href="<?php echo base_url('pedidos/view/' . $pedido->id); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detalhes
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>