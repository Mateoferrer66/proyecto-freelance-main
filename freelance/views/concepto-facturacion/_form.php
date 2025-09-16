<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="concepto-facturacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'iva_id')->textInput() ?>

    <?= $form->field($model, 'cof_codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cof_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cof_clasificacion')->dropDownList([ 'estandar' => 'Estandar', 'opcional' => 'Opcional', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'cof_eliminado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
