<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Iva $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="iva-form">

    <?php $form = ActiveForm::begin([
        'id' => 'iva-form',
        'enableAjaxValidation' => true, // Habilitar validación AJAX
        'enableClientValidation' => false, // Deshabilitar validación del lado del cliente para depender de la respuesta del servidor
    ]); ?>

    <?= $form->field($model, 'iva_porcentaje')->textInput() ?>

    <?= $form->field($model, 'iva_concepto')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>