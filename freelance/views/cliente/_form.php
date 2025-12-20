<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TipoDocIdentidad;
use app\models\Pais;
use app\models\Provincia;
use app\models\FormaDePago;
use app\models\Socio;
use app\models\Iva;

/** @var yii\web\View $this */
/** @var app\models\Cliente $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerJs(
    "\n$('#cliente-pai_id').change(function(){\n    var paisId = $(this).val();\n    if(paisId){\n        $.get('index.php?r=cliente/provincias-por-pais', {id: paisId}, function(data){\n            var options = '<option value=\"\">Seleccionar provincia</option>';\n            $.each(data, function(index, value){\n                options += '<option value=\"'+value.id+'\">'+value.name+'</option>';\n            });\n            $('#cliente-prv_id').html(options);\n        });\n    } else {\n        $('#cliente-prv_id').html('<option value=\"\">Seleccionar provincia</option>');\n    }\n});\n"
);
?>

<div class="page-content">
    <h6 class="mb-0 text-uppercase">DATOS DEL CLIENTE <dl>* Datos obligatorios</dl></h6>
    <hr/>
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body p-5">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form',
                            'id' => 'clienteForm',
                        ]
                    ]); ?>

                    <?= $form->errorSummary($model, ['class' => 'alert alert-danger'])
                    ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_numero')->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_nombre')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_persona_contacto')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_numdocide')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_tel1')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_tel2')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <?= $form->field($model, 'cli_direccion')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_poblacion')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_codpostal')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_email')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_cuenta_contable')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'iva_id')->dropDownList(ArrayHelper::map(Iva::find()->all(), 'iva_id', 'iva_nombre'), ['prompt' => 'Seleccione IVA'])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'fdp_id')->dropDownList(ArrayHelper::map(FormaDePago::find()->all(), 'fdp_id', 'fdp_nombre'), ['prompt' => 'Seleccione Forma de Pago'])
                            ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'soc_id')->dropDownList(ArrayHelper::map(Socio::find()->all(), 'soc_id', 'soc_nombre'), ['prompt' => 'Seleccione Socio'])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'cli_estado')->dropDownList([ 'Activo' => 'Activo', 'Inactivo' => 'Inactivo', ], ['prompt' => ''])
                            ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <?= $form->field($model, 'cli_observaciones')->textarea(['rows' => 6]) ?>
                        </div>
                    </div>

                    <hr>

                    <div class="col-md-12">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success px-5 radius-30'])
                        ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>