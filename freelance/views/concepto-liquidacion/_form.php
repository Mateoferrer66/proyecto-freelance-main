<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Iva;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\ConceptoLiquidacion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="concepto-liquidacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'col_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'col_clasificacion')->dropDownList($model::optsColClasificacion(), ['prompt' => 'Seleccione Clasificación']) ?>

    <?= $form->field($model, 'col_tipo')->dropDownList($model::optsColTipo(), ['prompt' => 'Seleccione Tipo']) ?>

    <?= $form->field($model, 'col_porcentaje')->textInput() ?>

    <?= $form->field($model, 'col_valor')->textInput() ?>

    <?= $form->field($model, 'col_eliminado')->textInput() ?>

    <div class="form-group d-flex justify-content-end">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>