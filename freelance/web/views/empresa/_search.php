<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\EmpresaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="empresa-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'emp_id') ?>

    <?= $form->field($model, 'emp_razon_social') ?>

    <?= $form->field($model, 'tdo_id') ?>

    <?= $form->field($model, 'emp_numdocide') ?>

    <?= $form->field($model, 'emp_direccion') ?>

    <?php // echo $form->field($model, 'emp_codpostal') ?>

    <?php // echo $form->field($model, 'emp_poblacion') ?>

    <?php // echo $form->field($model, 'emp_telefono') ?>

    <?php // echo $form->field($model, 'emp_fax') ?>

    <?php // echo $form->field($model, 'emp_email') ?>

    <?php // echo $form->field($model, 'emp_regimen_segs') ?>

    <?php // echo $form->field($model, 'emp_ccc_segs') ?>

    <?php // echo $form->field($model, 'emp_tipo_segs') ?>

    <?php // echo $form->field($model, 'emp_razons_segs') ?>

    <?php // echo $form->field($model, 'emp_participaciones') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
