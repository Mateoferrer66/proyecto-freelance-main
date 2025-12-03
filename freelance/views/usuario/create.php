<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Usuario;

/** @var yii\web\View $this */
/** @var app\models\Usuario $model */

$this->title = 'CREAR USUARIO';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-content">
    <h6 class="mb-0 text-uppercase">CREAR USUARIO <dl>* Datos obligatorios</dl></h6>
    <hr/>
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body p-5">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form',
                            'id' => 'usuariosForm',
                            'enctype' => 'multipart/form-data'
                        ]
                    ]); ?>

                    <div class="card-title d-flex align-items-center">
                        <h5 class="mb-0 text-white">DATOS DEL USUARIO</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-12 col-md-4">
                            <?= $form->field($model, 'usu_nombre', [
                                'template' => "<label>Nombre *</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                            ])->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <?= $form->field($model, 'usu_apellido', [
                                'template' => "<label>Apellido *</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                            ])->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <?= $form->field($model, 'usu_email', [
                                'template' => "<label>Email *</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'type' => 'email', 'required' => true]
                            ])->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-8">
                            <?= $form->field($model, 'usu_password', [
                                'template' => "<label>Contraseña *</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                            ])->passwordInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <?= $form->field($model, 'usu_estado', [
                                'template' => "<label>Estado *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                app\models\Usuario::optsUsuEstado(),
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true]
                            ) ?>
                        </div>
                    </div>

                    <hr>

                    <div class="col-12">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success px-5 radius-30'])
 ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
