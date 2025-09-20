<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Banco $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="banco-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ban_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ban_numcuenta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ban_eliminado')->textInput() ?>

    <div class="form-group d-flex justify-content-end">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
