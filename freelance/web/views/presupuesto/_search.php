<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PresupuestoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="presupuesto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'pre_id') ?>

    <?= $form->field($model, 'pre_numero') ?>

    <?= $form->field($model, 'pre_logo') ?>

    <?= $form->field($model, 'pre_fecha') ?>

    <?= $form->field($model, 'pre_language') ?>

    <?php // echo $form->field($model, 'cli_id') ?>

    <?php // echo $form->field($model, 'soc_id') ?>

    <?php // echo $form->field($model, 'fdp_id') ?>

    <?php // echo $form->field($model, 'pre_subtotal') ?>

    <?php // echo $form->field($model, 'pre_iva') ?>

    <?php // echo $form->field($model, 'pre_gastos_suplidos') ?>

    <?php // echo $form->field($model, 'pre_total') ?>

    <?php // echo $form->field($model, 'pre_observaciones') ?>

    <?php // echo $form->field($model, 'pre_eliminado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
