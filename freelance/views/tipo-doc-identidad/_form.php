<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TipoDocIdentidad $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tipo-doc-identidad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tdo_nombre')->textInput(['maxlength' => true]) ?>

    <div class="form-group d-flex justify-content-end">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>