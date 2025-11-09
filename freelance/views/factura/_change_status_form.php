<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Factura;

/** @var yii\web\View $this */
/** @var app\models\Factura $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="factura-form">

    <?php $form = ActiveForm::begin(['id' => 'change-status-form']); ?>

    <?= $form->field($model, 'fac_situacion')->dropDownList(Factura::optsFacEstado(), ['prompt' => 'Seleccione una situación']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
