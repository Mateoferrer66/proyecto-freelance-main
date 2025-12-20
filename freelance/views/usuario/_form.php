<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Usuario $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="usuario-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'usu_nombre')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'usu_apellido')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'usu_email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'usu_rol')->dropDownList(\app\models\Usuario::optsUsuRol(), ['prompt' => 'Seleccione Rol']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'usu_password')->passwordInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?php $model->usu_estado = $model->isNewRecord ? 1 : ($model->usu_estado == 'Activo' ? 1 : 0); ?>
            <?= $form->field($model, 'usu_estado')->checkbox() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success px-5 radius-30']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>