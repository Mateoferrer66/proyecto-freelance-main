<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Pais;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Provincia $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="provincia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'prv_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pai_id')->dropDownList(
        ArrayHelper::map(Pais::find()->all(), 'pai_id', 'pai_nombre'),
        ['prompt' => 'Seleccione País']
    ) ?>

    <div class="form-group d-flex justify-content-end">
         <?= Html::submitButton('Guardar', ['class' => 'btn btn-success radius-30']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
