<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TipoDocIdentidad $model */
/** @var yii\widgets\ActiveForm $form */

$form = ActiveForm::begin([
    'id' => 'tipo-doc-form',
    'enableAjaxValidation' => false,
]);

echo $form->field($model, 'tdo_codigo')->textInput(['maxlength' => true]);
echo $form->field($model, 'tdo_nombre')->textInput(['maxlength' => true]);

echo Html::submitButton('Guardar', ['class' => 'btn btn-primary float-end']);

ActiveForm::end();
?>