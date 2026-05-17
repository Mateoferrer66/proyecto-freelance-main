<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Socio;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidacion */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Nueva Liquidación Socio';
$this->params['breadcrumbs'][] = ['label' => 'Transferencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// List of socios for dropdown
$socios = ArrayHelper::map(Socio::find()->where(['soc_eliminado' => 0])->all(), 'soc_id', function($soc) {
    return $soc->soc_numero . ' - ' . $soc->soc_nombre . ' ' . $soc->soc_apellido;
});

?>

<div class="page-content">
    <div class="col d-flex justify-content-between align-items-start mb-3">
        <h6 class="mb-0 text-uppercase">Agregar Liquidación Socio</h6>
        <div>
            <span class="text-danger" style="font-style: italic; font-size: 0.85rem;">* Datos obligatorios.</span>
        </div>
    </div>
    <hr />

    <div class="card bg-transparent shadow-none border-0">
        <div class="card-body p-0">
            <?php $form = ActiveForm::begin([
                'id' => 'liquidacion-form',
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

            <!-- Relación de Facturas a Liquidar -->
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="mb-2 text-white mt-3">Relación de Facturas a Liquidar</h6>
                    <hr class="mt-0 mb-3" style="border-top: 1px dotted rgba(255, 255, 255, 0.4);" />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Factura No</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="factura_search" id="factura_search" placeholder="">
                        <button class="btn btn-outline-secondary" type="button" id="btn-search-factura">
                            <i class="bx bx-search-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Datos del Socio -->
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="mb-2 text-white mt-3">Datos del Socio</h6>
                    <hr class="mt-0 mb-3" style="border-top: 1px dotted rgba(255, 255, 255, 0.4);" />
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'soc_id')->dropDownList($socios, ['prompt' => 'Seleccione un Socio...', 'class' => 'form-select', 'required' => true])->label('Socio <span class="text-danger">*</span>') ?>
                </div>
            </div>

            <!-- Seguridad Social -->
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="mb-2 text-white mt-3">Seguridad Social</h6>
                    <hr class="mt-0 mb-3" style="border-top: 1px dotted rgba(255, 255, 255, 0.4);" />
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'liq_irpf')->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control'])->label('IRPF (%)') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'liq_irpf_valor')->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control'])->label('Valor IRPF') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'liq_ret_imp_soc')->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control'])->label('Retención Imp. Sociedades (%)') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'liq_ret_imp_soc_valor')->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control'])->label('Valor Imp. Sociedades') ?>
                </div>
            </div>

            <!-- Conceptos de Liquidación -->
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="mb-2 text-white mt-3">Conceptos de Liquidación</h6>
                    <hr class="mt-0 mb-3" style="border-top: 1px dotted rgba(255, 255, 255, 0.4);" />
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'liq_total_neto')->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control'])->label('Total Neto') ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'liq_total_gastos')->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control'])->label('Total Gastos') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'liq_total_retenciones')->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control'])->label('Total Retenciones') ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'liq_iva_facturas')->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control'])->label('IVA Facturas') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'liq_ingreso_liquido')->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control'])->label('Ingreso Líquido') ?>
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


