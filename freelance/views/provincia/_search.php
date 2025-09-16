<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProvinciaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="provincia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'prv_id') ?>

    <?= $form->field($model, 'pai_id') ?>

    <?= $form->field($model, 'prv_nombre') ?>

    <?= $form->field($model, 'prv_eliminada') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
