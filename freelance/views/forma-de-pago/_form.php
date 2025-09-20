<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\FormaDePago $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="forma-de-pago-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fdp_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fdp_eliminada')->textInput() ?>

    <div class="form-group d-flex justify-content-end">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>