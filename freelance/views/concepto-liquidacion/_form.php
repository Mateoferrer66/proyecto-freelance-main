<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ConceptoLiquidacion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="concepto-liquidacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'col_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'col_clasificacion')->dropDownList([ 'estandar' => 'Estandar', 'opcional' => 'Opcional', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'col_tipo')->dropDownList([ 'porcentaje' => 'Porcentaje', 'valor' => 'Valor', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'col_porcentaje')->textInput() ?>

    <?= $form->field($model, 'col_valor')->textInput() ?>

    <?= $form->field($model, 'col_eliminado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
