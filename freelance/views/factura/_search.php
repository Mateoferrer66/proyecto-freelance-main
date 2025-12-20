<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\FacturaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="factura-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'fac_id') ?>

    <?= $form->field($model, 'fac_numero') ?>

    <?= $form->field($model, 'fac_logo') ?>

    <?= $form->field($model, 'fac_fecha') ?>

    <?= $form->field($model, 'fac_language') ?>

    <?php // echo $form->field($model, 'fac_money') ?>

    <?php // echo $form->field($model, 'cli_id') ?>

    <?php // echo $form->field($model, 'soc_id') ?>

    <?php // echo $form->field($model, 'fdp_id') ?>

    <?php // echo $form->field($model, 'fac_estado') ?>

    <?php // echo $form->field($model, 'fac_situacion') ?>

    <?php // echo $form->field($model, 'fac_fecha_situacion') ?>

    <?php // echo $form->field($model, 'fac_subtotal') ?>

    <?php // echo $form->field($model, 'fac_iva') ?>

    <?php // echo $form->field($model, 'fac_gastos_suplidos') ?>

    <?php // echo $form->field($model, 'fac_total') ?>

    <?php // echo $form->field($model, 'fac_observaciones') ?>

    <?php // echo $form->field($model, 'fac_exportada') ?>

    <?php // echo $form->field($model, 'fac_eliminada') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
