<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Iva $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="iva-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'iva_porcentaje')->textInput() ?>

    <?= $form->field($model, 'iva_concepto')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success radius-30']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>