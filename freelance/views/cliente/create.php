<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TipoDocIdentidad;
use app\models\Pais;
use app\models\Provincia;
use app\models\Cliente;

/** @var yii\web\View $this */
/** @var app\models\Cliente $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'CREAR CLIENTE';
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-content" style="margin-top: 3.4rem;">
    <h6 class="mb-0 text-uppercase">CREAR CLIENTE <dl>* Datos obligatorios</dl></h6>
    <hr/>
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body p-5">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form',
                            'id' => 'clientesForm',
                            'enctype' => 'multipart/form-data'
                        ]
                    ]); ?>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <?= $form->field($model, 'cli_numero', [
                                'template' => "<label>Número Cliente*</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'placeholder' => 'xxxxxx', 'required' => true]
                            ])->textInput() ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center">
                        <h5 class="mb-0 text-white">DATOS PERSONALES</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <?= $form->field($model, 'tdo_id', [
                                'template' => "<label>Tipo Documento *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                ArrayHelper::map(TipoDocIdentidad::find()->all(), 'tdo_id', 'tdo_nombre'),
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true]
                            ) ?>
                        </div>
                        <div class="col-md-4">
                             <?= $form->field($model, 'cli_docinipais', [
                                'template' => "<label>Iniciales país</label>\n{input}\n{hint}\n{error}"
                            ])->textInput(['maxlength' => true, 'class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'cli_numdocide', [
                                'template' => "<label>Número identificación fiscal *</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                            ])->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_nombre', [
                                'template' => "<label>Nombre Razón Social*</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                            ])->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_persona_contacto', [
                                'template' => "<label>Persona de contacto *</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                            ])->textInput() ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">DATOS DE CONTACTO</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <?= $form->field($model, 'cli_tel1', [
                                'template' => "<label>Teléfono 1</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3']
                            ])->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'cli_tel2', [
                                'template' => "<label>Teléfono 2</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3']
                            ])->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'cli_direccion', [
                                'template' => "<label>Dirección</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3']
                            ])->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'cli_poblacion', [
                                'template' => "<label>Población</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3']
                            ])->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'prv_id', [
                                'template' => "<label>Provincia *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                ArrayHelper::map(Provincia::find()->all(), 'prv_id', 'prv_nombre'),
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true]
                            ) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'pai_id', [
                                'template' => "<label>País *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                ArrayHelper::map(Pais::find()->all(), 'pai_id', 'pai_nombre'),
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true]
                            ) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'cli_codpostal', [
                                'template' => "<label>Código postal</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3']
                            ])->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'cli_email', [
                                'template' => "<label>E-mail *</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'type' => 'email']
                            ])->textInput() ?>
                        </div>
                    </div>

                    <hr>

                    <div class="col-md-12">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success px-5 radius-30']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>