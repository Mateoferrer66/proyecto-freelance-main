<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BancoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="banco-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ban_id') ?>

    <?= $form->field($model, 'ban_nombre') ?>

    <?= $form->field($model, 'ban_numcuenta') ?>

    <?= $form->field($model, 'ban_eliminado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
