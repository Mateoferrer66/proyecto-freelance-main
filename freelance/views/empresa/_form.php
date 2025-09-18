<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Empresa $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="empresa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'emp_razon_social')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tdo_id')->textInput() ?>

    <?= $form->field($model, 'emp_numdocide')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_codpostal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_poblacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_regimen_segs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_ccc_segs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_tipo_segs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_razons_segs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_participaciones')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
