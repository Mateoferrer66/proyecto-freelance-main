<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Factura;

/** @var yii\web\View $this */
/** @var app\models\Factura $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerJs(<<<'JS'
$(function() {
    // Inicializar el datepicker para el nuevo campo
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        time: false,
        lang: 'es',
        weekStart: 1
    });
});
JS);
?>

<div class="factura-form">

    <?php $form = ActiveForm::begin([
        'id' => 'change-status-form',
        'options' => ['data-pjax' => 0] // Asegura que el form no se someta por Pjax
    ]); ?>

    <?= $form->field($model, 'fac_situacion')->dropDownList(Factura::optsFacSituacion(), ['prompt' => 'Seleccione una situación']) ?>

    <?= $form->field($model, 'fac_fecha_situacion')->textInput(['class' => 'form-control datepicker', 'value' => $model->fac_fecha_situacion ? Yii::$app->formatter->asDate($model->fac_fecha_situacion, 'php:Y-m-d') : '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
