<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">Editar Cupom</h5>
    </div>
    <div class="card-body">
        <?php echo form_open('cupons/edit/' . $cupom->id); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="codigo">Código do Cupom *</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo $cupom->codigo; ?>" required>
                        <small class="text-muted">Ex: BEMVINDO10, FRETEGRATIS, etc.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="data_validade">Data de Validade *</label>
                        <input type="date" class="form-control" id="data_validade" name="data_validade" value="<?php echo $cupom->data_validade; ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tipo_desconto">Tipo de Desconto *</label>
                        <select class="form-control" id="tipo_desconto" name="tipo_desconto" required>
                            <option value="percentual" <?php echo ($cupom->tipo_desconto == 'percentual') ? 'selected' : ''; ?>>Percentual (%)</option>
                            <option value="fixo" <?php echo ($cupom->tipo_desconto == 'fixo') ? 'selected' : ''; ?>>Valor Fixo (R$)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="desconto">Valor do Desconto *</label>
                        <input type="number" class="form-control" id="desconto" name="desconto" step="0.01" min="0" value="<?php echo $cupom->desconto; ?>" required>
                        <small class="text-muted" id="desconto-info">
                            <?php if ($cupom->tipo_desconto == 'percentual'): ?>
                                Para percentual, use valores de 1 a 100.
                            <?php else: ?>
                                Informe o valor fixo do desconto em reais.
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="valor_minimo">Valor Mínimo da Compra (R$) *</label>
                        <input type="number" class="form-control" id="valor_minimo" name="valor_minimo" step="0.01" min="0" value="<?php echo $cupom->valor_minimo; ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="ativo" name="ativo" <?php echo ($cupom->ativo) ? 'checked' : ''; ?>>
                    <label class="custom-control-label" for="ativo">Cupom Ativo</label>
                </div>
            </div>
            
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
                <a href="<?php echo base_url('cupons'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    jQueryReady(function($) {
        // Código para maiúsculas
        $('#codigo').on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });
        
        // Ajustar texto de ajuda do desconto conforme o tipo
        $('#tipo_desconto').change(function() {
            if ($(this).val() === 'percentual') {
                $('#desconto-info').text('Para percentual, use valores de 1 a 100.');
            } else {
                $('#desconto-info').text('Informe o valor fixo do desconto em reais.');
            }
        });
    });
</script>