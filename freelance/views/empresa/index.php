<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TipoDocIdentidad;

/** @var yii\web\View $this */
/** @var app\models\Empresa $empresa */
/** @var app\models\Configuracion $configuracion */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Datos de la Empresa';
$this->params['breadcrumbs'] = [];

?>

<?= $this->render('@app/views/layouts/_orangemenu') ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e2f;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #2a2a3b;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            text-transform: uppercase;
            color: #ffa500;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-bar input {
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            width: 300px;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #ffa500;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            text-transform: uppercase;
        }

        .buttons button:hover {
            background-color: #ff8c00;
        }
    </style>

<div class="empresa-form-container">

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

    <h2 class="form-section-title">DATOS DE LA EMPRESA</h2>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($empresa, 'emp_razon_social')->textInput(['maxlength' => true])->label('Nombre/Razón Social') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($empresa, 'tdo_id')->dropDownList(
                ArrayHelper::map(TipoDocIdentidad::find()->all(), 'tdo_id', 'tdo_nombre'),
                ['prompt' => 'Seleccione']
            )->label('Tipo Documento *') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($empresa, 'emp_numdocide')->textInput(['maxlength' => true])->label('Número identificación Fiscal') ?>
        </div>
    </div>

    <h2 class="form-section-title">DATOS DE CONTACTO</h2>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($empresa, 'emp_telefono')->textInput(['maxlength' => true])->label('Teléfono*') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($empresa, 'emp_fax')->textInput(['maxlength' => true])->label('Fax') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($empresa, 'emp_direccion')->textInput(['maxlength' => true])->label('Dirección*') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($empresa, 'emp_poblacion')->textInput(['maxlength' => true])->label('Población') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($empresa, 'emp_codpostal')->textInput(['maxlength' => true])->label('Código postal') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($empresa, 'emp_email')->textInput(['maxlength' => true])->label('E-mail *') ?>
        </div>
    </div>

    <h2 class="form-section-title">DATOS SEGURIDAD SOCIAL</h2>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($empresa, 'emp_regimen_segs')->textInput(['maxlength' => true])->label('Régimen (seguridad social)*') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($empresa, 'emp_ccc_segs')->textInput(['maxlength' => true])->label('CCC (seguridad social)*') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($empresa, 'emp_tipo_segs')->textInput(['maxlength' => true])->label('Tipo de empresa (seguridad social)*') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($configuracion, 'con_base_cotizacion_ss')->textInput()->label('Base cotización (€)*') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($empresa, 'emp_razons_segs')->textInput(['maxlength' => true])->label('Razón Social (seguridad social)*') ?>
        </div>
    </div>

    <h2 class="form-section-title">OTROS</h2>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($configuracion, 'con_retencion_imp_soc')->textInput()->label('Retención a cuenta impto/sdades (%)') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($empresa, 'emp_participaciones')->textInput()->label('Participaciones') ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>