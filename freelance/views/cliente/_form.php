<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Cliente $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cliente-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cli_numero')->textInput() ?>

    <?= $form->field($model, 'cli_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cli_persona_contacto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tdo_id')->textInput() ?>

    <?= $form->field($model, 'cli_docinipais')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cli_numdocide')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cli_feccaddoc')->textInput() ?>

    <?= $form->field($model, 'cli_tel1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cli_tel2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cli_direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pai_id')->textInput() ?>

    <?= $form->field($model, 'prv_id')->textInput() ?>

    <?= $form->field($model, 'cli_poblacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cli_codpostal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cli_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cli_cuenta_contable')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iva_id')->textInput() ?>

    <?= $form->field($model, 'fdp_id')->textInput() ?>

    <?= $form->field($model, 'soc_id')->textInput() ?>

    <?= $form->field($model, 'cli_observaciones')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'cli_estado')->dropDownList([ 'Activo' => 'Activo', 'Inactivo' => 'Inactivo', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'cli_exportado')->textInput() ?>

    <?= $form->field($model, 'cli_eliminado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
