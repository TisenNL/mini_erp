<div class="mb-4">
    <a href="<?php echo base_url('cupons/create'); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Adicionar Novo Cupom
    </a>
</div>

<?php if (empty($cupons)): ?>
    <div class="alert alert-info">
        Nenhum cupom cadastrado. Clique no botão acima para adicionar o primeiro cupom.
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Desconto</th>
                            <th>Valor Mínimo</th>
                            <th>Validade</th>
                            <th>Status</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cupons as $cupom): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $cupom->codigo; ?></strong>
                                </td>
                                <td>
                                    <?php if ($cupom->tipo_desconto == 'percentual'): ?>
                                        <?php echo $cupom->desconto; ?>%
                                    <?php else: ?>
                                        R$ <?php echo number_format($cupom->desconto, 2, ',', '.'); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    R$ <?php echo number_format($cupom->valor_minimo, 2, ',', '.'); ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($cupom->data_validade)); ?>
                                </td>
                                <td>
                                    <?php if ($cupom->ativo): ?>
                                        <span class="badge badge-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('cupons/edit/' . $cupom->id); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $cupom->id; ?>">
                                        <i class="fas fa-trash"></i> Excluir
                                    </a>
                                    
                                    <!-- Modal de Exclusão -->
                                    <div class="modal fade" id="deleteModal<?php echo $cupom->id; ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Confirmar Exclusão</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Tem certeza que deseja excluir o cupom <strong><?php echo $cupom->codigo; ?></strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <a href="<?php echo base_url('cupons/delete/' . $cupom->id); ?>" class="btn btn-danger">
                                                        Sim, Excluir
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>