<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Provincia $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="provincia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pai_id')->textInput() ?>

    <?= $form->field($model, 'prv_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prv_eliminada')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
