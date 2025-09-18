<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Consecutivo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="consecutivo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'con_serie')->dropDownList([ 'F' => 'F', 'L' => 'L', 'S' => 'S', 'C' => 'C', 'P' => 'P', 'PL' => 'PL', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'con_consecutivo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
