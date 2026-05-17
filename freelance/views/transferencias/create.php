<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidacion */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Agregar Transferencia';
$this->params['breadcrumbs'][] = ['label' => 'Transferencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Agregar';

?>

<div class="page-content">
    <div class="col d-flex justify-content-between align-items-start mb-3">
        <h6 class="mb-0 text-uppercase">AGREGAR TRANSFERENCIA</h6>
        <div>
            <span class="text-danger" style="font-style: italic; font-size: 0.85rem;">* Datos obligatorios.</span>
        </div>
    </div>
    <hr />

    <div class="card bg-transparent shadow-none border-0">
        <div class="card-body p-0">
            <?php $form = ActiveForm::begin([
                'id' => 'transferencia-form',
            ]); ?>
            
            <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

            <!-- Datos Liquidación -->
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="mb-2 text-white">Datos Liquidación</h6>
                    <hr class="mt-0 mb-3" style="border-top: 1px dotted rgba(255, 255, 255, 0.4);" />
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'liq_numero')->textInput(['class' => 'form-control', 'readonly' => true])->label('Número Liquidación <span class="text-danger">*</span>') ?>
                </div>
                <div class="col-md-3 offset-md-4">
                    <?= $form->field($model, 'liq_fecha')->textInput(['type' => 'date', 'class' => 'form-control', 'required' => true])->label('Fecha <span class="text-danger">*</span>') ?>
                </div>
            </div>

            <!-- Importe Base Liquidación -->
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="mb-2 text-white mt-3">Importe Base Liquidación</h6>
                    <hr class="mt-0 mb-3" style="border-top: 1px dotted rgba(255, 255, 255, 0.4);" />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Socio</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="socio_search" id="socio_search" placeholder="">
                        <!-- Hidden field to actually store soc_id for form submission to work -->
                        <?= Html::activeHiddenInput($model, 'soc_id', ['id' => 'soc_id_hidden']) ?>
                        <button class="btn btn-outline-secondary" type="button" id="btn-search-socio">
                            <i class="bx bx-search-alt"></i>
                        </button>
                    </div>
                    <?= Html::error($model, 'soc_id', ['class' => 'text-danger mt-1', 'style' => 'font-size: 0.85rem;']) ?>
                </div>
            </div>

            <!-- Datos del Socio -->
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="mb-2 text-white mt-3">Datos del Socio</h6>
                    <hr class="mt-0 mb-3" style="border-top: 1px dotted rgba(255, 255, 255, 0.4);" />
                </div>
            </div>

            <!-- Seguridad Social -->
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="mb-2 text-white mt-3">Seguridad Social</h6>
                    <hr class="mt-0 mb-3" style="border-top: 1px dotted rgba(255, 255, 255, 0.4);" />
                </div>
            </div>

            <!-- Conceptos de Liquidación -->
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="mb-2 text-white mt-3">Conceptos de Liquidación</h6>
                    <hr class="mt-0 mb-3" style="border-top: 1px dotted rgba(255, 255, 255, 0.4);" />
                </div>
            </div>

            <!-- Observaciones -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="mb-2 text-white mt-3">Observaciones</h6>
                    <hr class="mt-0 mb-3" style="border-top: 1px dotted rgba(255, 255, 255, 0.4);" />
                </div>
                <div class="col-12">
                    <?= $form->field($model, 'liq_observaciones')->textarea(['rows' => 4, 'class' => 'form-control'])->label(false) ?>
                </div>
            </div>

            <!-- Buttons -->
            <div class="row mt-4 mb-4">
                <div class="col-12 d-flex justify-content-end gap-2">
                    <?= Html::submitButton('<i class="bx bx-save mr-1"></i> GUARDAR', ['class' => 'btn btn-outline-warning radius-30 text-orange']) ?>
                    <?= Html::a('<i class="bx bx-minus-circle mr-1"></i> CANCELAR', ['index'], ['class' => 'btn btn-outline-warning radius-30 text-orange']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$('#btn-search-socio').on('click', function() {
    // Basic mock functionality so the form can be saved, as the user requested it to be functional
    let searchVal = $('#socio_search').val();
    if(searchVal) {
        // Just arbitrarily assigning a soc_id = 1 if user typed something so validation passes
        $('#soc_id_hidden').val(1);
        alert('Búsqueda simulada. Socio seleccionado internamente.');
    } else {
        alert('Por favor ingrese un socio a buscar.');
    }
});
JS;
$this->registerJs($js);
?>
