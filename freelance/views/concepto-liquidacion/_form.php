<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ConceptoLiquidacion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="concepto-liquidacion-form">

    <?php $form = ActiveForm::begin([
        'id' => 'concepto-liquidacion-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
    ]); ?>

    <?= $form->field($model, 'col_codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'col_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'col_clasificacion')->dropDownList($model::optsColClasificacion(), ['prompt' => 'Seleccione Clasificación']) ?>

    <?= $form->field($model, 'col_tipo')->dropDownList($model::optsColTipo(), ['prompt' => 'Seleccione Tipo']) ?>

    <?= $form->field($model, 'col_porcentaje')->textInput() ?>

    <?= $form->field($model, 'col_valor')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => 'btn btn-primary float-end'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>