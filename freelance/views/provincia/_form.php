<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Provincia $model */
/** @var yii\widgets\ActiveForm $form */

$form = ActiveForm::begin([
    'id' => 'provincia-form',
    'enableAjaxValidation' => true, // Habilitar validación AJAX
    'enableClientValidation' => false, // Deshabilitar validación del lado del cliente para depender de la respuesta del servidor
]);

echo $form->field($model, 'pro_codigo')->textInput(['maxlength' => true]);
echo $form->field($model, 'pro_nombre')->textInput(['maxlength' => true]);

echo Html::submitButton('Guardar', ['class' => 'btn btn-primary float-end']);

ActiveForm::end();
?>