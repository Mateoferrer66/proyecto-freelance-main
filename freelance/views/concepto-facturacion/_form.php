<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Iva;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="concepto-facturacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cof_codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cof_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iva_id')->dropDownList(
        ArrayHelper::map(Iva::find()->all(), 'iva_id', 'iva_concepto'),
        ['prompt' => 'Seleccione IVA']
    ) ?>

    <?= $form->field($model, 'cof_clasificacion')->dropDownList(
        $model::optsCofClasificacion(),
        ['prompt' => 'Seleccione Clasificación']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success radius-30']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>