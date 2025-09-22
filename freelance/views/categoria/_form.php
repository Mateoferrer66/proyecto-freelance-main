<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Categoria $model */
/** @var yii\widgets\ActiveForm $form */

$form = ActiveForm::begin([
    'id' => 'categoria-form',
    // Enable AJAX validation for immediate feedback
    'enableAjaxValidation' => true,
    // Disable client-side validation to rely on server-side response
    'enableClientValidation' => false,
]);

echo $form->field($model, 'cat_codigo')->textInput(['maxlength' => true]);
echo $form->field($model, 'cat_nombre')->textInput(['maxlength' => true]);

echo Html::submitButton('Guardar', ['class' => 'btn btn-primary float-end']);

ActiveForm::end();
?>