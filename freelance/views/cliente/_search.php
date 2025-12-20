<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ClienteSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cliente-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cli_id') ?>

    <?= $form->field($model, 'cli_numero') ?>

    <?= $form->field($model, 'cli_nombre') ?>

    <?= $form->field($model, 'cli_persona_contacto') ?>

    <?= $form->field($model, 'tdo_id') ?>

    <?php // echo $form->field($model, 'cli_docinipais') ?>

    <?php // echo $form->field($model, 'cli_numdocide') ?>

    <?php // echo $form->field($model, 'cli_feccaddoc') ?>

    <?php // echo $form->field($model, 'cli_tel1') ?>

    <?php // echo $form->field($model, 'cli_tel2') ?>

    <?php // echo $form->field($model, 'cli_direccion') ?>

    <?php // echo $form->field($model, 'pai_id') ?>

    <?php // echo $form->field($model, 'prv_id') ?>

    <?php // echo $form->field($model, 'cli_poblacion') ?>

    <?php // echo $form->field($model, 'cli_codpostal') ?>

    <?php // echo $form->field($model, 'cli_email') ?>

    <?php // echo $form->field($model, 'cli_cuenta_contable') ?>

    <?php // echo $form->field($model, 'iva_id') ?>

    <?php // echo $form->field($model, 'fdp_id') ?>

    <?php // echo $form->field($model, 'soc_id') ?>

    <?php // echo $form->field($model, 'cli_observaciones') ?>

    <?php // echo $form->field($model, 'cli_estado') ?>

    <?php // echo $form->field($model, 'cli_exportado') ?>

    <?php // echo $form->field($model, 'cli_eliminado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
