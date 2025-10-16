<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TipoDocIdentidad;
use app\models\Pais;
use app\models\Provincia;
use app\models\Cliente;

use app\models\FormaDePago;

/** @var yii\web\View $this */
/** @var app\models\Cliente $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'CREAR CLIENTE';
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// JavaScript para la lógica de campos dinámicos y el datepicker
use yii\helpers\Url; // Agregado

$provinciasPorPaisUrl = Url::to(['cliente/provincias-por-pais']);

$js = <<<JS
$(function(){
    // --- Lógica para mostrar/ocultar campos ---
    function toggleFields() {
        var tipoDoc = $('#cliente-tdo_id').val();
        var campoDocIniPais = $('.field-cliente-cli_docinipais');
        var campoFecCadDoc = $('.field-cliente-cli_feccaddoc');

        campoDocIniPais.hide();
        campoFecCadDoc.hide();

        if (tipoDoc == '3' || tipoDoc == '4') { // CIF y CIF Intracomunitario
            campoDocIniPais.show();
        }
        
        if (tipoDoc == '6') { // NIE
            campoFecCadDoc.show();
        }
    }

    toggleFields(); // Ejecutar al cargar
    $('#cliente-tdo_id').on('change', toggleFields); // Ejecutar al cambiar

    // --- Inicialización del Datepicker ---
    $('#cliente-cli_feccaddoc').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        time: false, // No mostrar selector de hora
        lang: 'es',
        weekStart: 1
    });

    // --- Lógica para cargar provincias dinámicamente ---
    $('#cliente-pai_id').on('change', function() {
        var paiId = $(this).val();
        var provinciaDropdown = $('#cliente-prv_id');
        provinciaDropdown.empty().append('<option value="">Cargando...</option>'); // Limpiar y mostrar "Cargando..."

        if (paiId) {
            var url = "{$provinciasPorPaisUrl}"; // Usar la variable PHP interpolada
            console.log('AJAX URL:', url); // Ahora esto debería mostrar la URL correcta
            $.ajax({
                url: url,
                type: 'GET',
                data: {id: paiId},
                dataType: 'json',
                success: function(data) {
                    provinciaDropdown.empty().append('<option value="">Seleccione</option>');
                    $.each(data, function(key, provincia) {
                        provinciaDropdown.append($('<option></option>').attr('value', provincia.id).text(provincia.name));
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown, jqXHR);
                    provinciaDropdown.empty().append('<option value="">Error al cargar provincias</option>');
                }
            });
        } else {
            provinciaDropdown.empty().append('<option value="">Seleccione</option>');
        }
    });

    // Disparar el evento change al cargar la página si ya hay un país seleccionado
    if ($('#cliente-pai_id').val()) {
        $('#cliente-pai_id').trigger('change');
    }
});
JS;

$this->registerJs($js);

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

                    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

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
                                'template' => "<label>Iniciales país</label>\n{input}\n{hint}\n{error}",
                            ])->textInput(['maxlength' => true, 'class' => 'form-control mb-3']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'cli_feccaddoc', [
                                'template' => "<label>Fecha Caducidad Del Documento De Identidad</label>\n{input}\n{hint}\n{error}",
                            ])->textInput(['class' => 'form-control mb-3', 'placeholder' => 'YYYY-MM-DD']) // Quitado type=date 
                            ?>
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
                            <?= $form->field($model, 'pai_id', [
                                'template' => "<label>País *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                ArrayHelper::map(Pais::find()->all(), 'pai_id', 'pai_nombre'),
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true, 'id' => 'cliente-pai_id']
                            ) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'prv_id', [
                                'template' => "<label>Provincia *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                [], // Se inicializa vacío, se llenará con AJAX
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true, 'id' => 'cliente-prv_id']
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

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">DATOS DE FACTURACIÓN</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <?= $form->field($model, 'fdp_id', [
                                'template' => "<label>Forma de Pago *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                ArrayHelper::map(app\models\FormaDePago::find()->all(), 'fdp_id', 'fdp_nombre'),
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true]
                            ) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'cli_estado', [
                                'template' => "<label>Estado *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                app\models\Cliente::optsCliEstado(),
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true]
                            ) ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">OBSERVACIONES</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <?= $form->field($model, 'cli_observaciones', [
                                'template' => "<label>Observaciones</label>\n{input}\n{hint}\n{error}",
                            ])->textarea(['rows' => 6, 'class' => 'form-control']) ?>
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