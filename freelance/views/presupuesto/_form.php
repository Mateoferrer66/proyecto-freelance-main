<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Presupuesto $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="presupuesto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pre_numero')->textInput() ?>

    <?= $form->field($model, 'pre_logo')->dropDownList([ 'socio' => 'Socio', 'empresa' => 'Empresa', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'pre_fecha')->textInput() ?>

    <?= $form->field($model, 'pre_language')->dropDownList([ 'en' => 'En', 'es' => 'Es', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'pre_money')->dropDownList([ 'Euros' => 'Euros', '£' => '£', 'US$' => 'US$', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'pre_fecha_situacion')->textInput() ?>

    <?= $form->field($model, 'pre_estado')->dropDownList($estados, ['prompt' => 'Seleccione']) ?>

    <?= $form->field($model, 'pre_situacion')->dropDownList($situaciones, ['prompt' => 'Seleccione']) ?>

    <?= $form->field($model, 'cli_id')->textInput() ?>

    <?= $form->field($model, 'soc_id')->textInput() ?>

    <?= $form->field($model, 'fdp_id')->textInput() ?>

    <?= $form->field($model, 'pre_subtotal')->textInput() ?>

    <?= $form->field($model, 'pre_iva')->textInput() ?>

    <?= $form->field($model, 'pre_gastos_suplidos')->textInput() ?>

    <?= $form->field($model, 'pre_total')->textInput() ?>

    <?= $form->field($model, 'pre_observaciones')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pre_eliminado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>