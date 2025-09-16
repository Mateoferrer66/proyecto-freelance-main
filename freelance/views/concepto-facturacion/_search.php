<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="concepto-facturacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cof_id') ?>

    <?= $form->field($model, 'iva_id') ?>

    <?= $form->field($model, 'cof_codigo') ?>

    <?= $form->field($model, 'cof_nombre') ?>

    <?= $form->field($model, 'cof_clasificacion') ?>

    <?php // echo $form->field($model, 'cof_eliminado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
