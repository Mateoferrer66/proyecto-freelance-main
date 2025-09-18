<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Empresa $model */
/** @var yii\widgets\ActiveForm $form */
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
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #2a2a3b;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #ffa500;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            flex: 1;
            font-size: 16px;
            margin-right: 10px;
        }

        .form-group input {
            flex: 2;
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        .form-group button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #ffa500;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            text-transform: uppercase;
        }

        .form-group button:hover {
            background-color: #ff8c00;
        }
    </style>
<div class="page-content">
    <h6 class="mb-0 text-uppercase">DATOS DE LA EMPRESA</h6>
    <hr />
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body p-5">
                    <?php $form = ActiveForm::begin(['options' => ['class' => 'form row', 'id' => 'facturaForm', 'enctype' => 'multipart/form-data']]); ?>

                    <!-- DATOS DE LA EMPRESA -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_razon_social')->label('Nombre/Razón Social')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'tdo_id')->label('Tipo Documento *')->dropDownList(['1' => 'CIF', '2' => 'OTRO', '3' => 'Otro'], ['prompt' => 'Seleccione', 'class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_numdocide')->label('Número identificación Fiscal')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                    </div>

                    <!-- DATOS DE CONTACTO -->
                    <div class="row mb-3">
                        <div class="card-title d-flex align-items-center mt-3">
                            <h5 class="mb-0 text-white">DATOS DE CONTACTO</h5>
                        </div>
                        <hr>
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_telefono')->label('Teléfono*')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_fax')->label('Fax')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_direccion')->label('Dirección*')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_poblacion')->label('Población')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_codpostal')->label('Código postal')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_email')->label('E-mail *')->textInput(['class' => 'form-control mb-3', 'type' => 'email']) ?>
                        </div>
                    </div>

                    <!-- DATOS SEGURIDAD SOCIAL -->
                    <div class="row mb-3">
                        <div class="card-title d-flex align-items-center">
                            <h5 class="mb-0 text-white">DATOS SEGURIDAD SOCIAL</h5>
                        </div>
                        <hr>
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_regimen_segs')->label('Régimen (seguridad social)*')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_ccc_segs')->label('CCC (seguridad social)*')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_tipo_segs')->label('Tipo de empresa (seguridad social)*')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                        <!-- No existe "base cotización" en tu modelo, si lo necesitas agrégalo en el modelo y la BD -->
                        <div class="col-md-4">
                            <?= $form->field($model, 'emp_razons_segs')->label('Razón Social (seguridad social)*')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                    </div>

                    <!-- OTROS -->
                    <div class="row mb-3">
                        <div class="card-title d-flex align-items-center mt-3">
                            <h5 class="mb-0 text-white">OTROS</h5>
                        </div>
                        <hr>
                        <!-- No existe "retención" en tu modelo, si lo necesitas agrégalo en el modelo y la BD -->
                        <div class="col-md-6">
                            <?= $form->field($model, 'emp_participaciones')->label('Participaciones')->textInput(['class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-12">
                            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success px-5 radius-30']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>