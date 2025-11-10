<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Presupuesto;

/** @var yii\web\View $this */
/** @var app\models\Presupuesto $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="presupuesto-form">

    <?php $form = ActiveForm::begin(['id' => 'change-status-form']); ?>

    <?= $form->field($model, 'pre_situacion')->dropDownList(Presupuesto::optsPreSituacion(), ['prompt' => 'Seleccione una situación']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
