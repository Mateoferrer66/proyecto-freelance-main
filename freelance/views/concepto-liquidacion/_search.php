<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ConceptoLiquidacionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="concepto-liquidacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'col_id') ?>

    <?= $form->field($model, 'col_nombre') ?>

    <?= $form->field($model, 'col_clasificacion') ?>

    <?= $form->field($model, 'col_tipo') ?>

    <?= $form->field($model, 'col_porcentaje') ?>

    <?php // echo $form->field($model, 'col_valor') ?>

    <?php // echo $form->field($model, 'col_eliminado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
