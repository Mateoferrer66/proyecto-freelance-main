<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Factura $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="factura-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fac_numero')->textInput() ?>

    <?= $form->field($model, 'fac_logo')->dropDownList([ 'socio' => 'Socio', 'empresa' => 'Empresa', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'fac_fecha')->textInput() ?>

    <?= $form->field($model, 'fac_language')->dropDownList([ 'en' => 'En', 'es' => 'Es', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'fac_money')->dropDownList([ 'Euros' => 'Euros', '£' => '£', 'US$' => 'US$', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'cli_id')->textInput() ?>

    <?= $form->field($model, 'soc_id')->textInput() ?>

    <?= $form->field($model, 'fdp_id')->textInput() ?>

    <?= $form->field($model, 'fac_estado')->dropDownList([ 'Sin Pagar' => 'Sin Pagar', 'Liquidada' => 'Liquidada', 'Liquidada Parcialmente' => 'Liquidada Parcialmente', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'fac_situacion')->dropDownList([ 'No Reclamada' => 'No Reclamada', 'Reclamada al Cliente' => 'Reclamada al Cliente', 'Reclamada al Socio' => 'Reclamada al Socio', 'Concurso de Acreedores' => 'Concurso de Acreedores', 'Cobrada por el Socio' => 'Cobrada por el Socio', 'Monitorio' => 'Monitorio', 'Burofax' => 'Burofax', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'fac_fecha_situacion')->textInput() ?>

    <?= $form->field($model, 'fac_subtotal')->textInput() ?>

    <?= $form->field($model, 'fac_iva')->textInput() ?>

    <?= $form->field($model, 'fac_gastos_suplidos')->textInput() ?>

    <?= $form->field($model, 'fac_total')->textInput() ?>

    <?= $form->field($model, 'fac_observaciones')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fac_exportada')->textInput() ?>

    <?= $form->field($model, 'fac_eliminada')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
