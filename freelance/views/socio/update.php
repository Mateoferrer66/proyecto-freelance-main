<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Categoria;
use app\models\TipoDocIdentidad;
use app\models\Provincia;
use app\models\Socio;
use app\models\FormaDePago;
use yii\web\View;

/** @var yii\web\View $this */
/** @var app\models\Cliente $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Editar Socio';
$this->params['breadcrumbs'][] = ['label' => 'Socios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// JavaScript para la lógica de campos dinámicos y el datepicker
use yii\helpers\Url; // Agregado

$provinciasPorPaisUrl = Url::to(['provincia/provincias-por-pais']);

$js = <<<JS
$(function(){
    // --- Inicialización del Datepicker ---
    $('#socio-soc_fecha').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        time: false, // No mostrar selector de hora
        lang: 'es',
        weekStart: 1
    });
    $('#socio-soc_fecnacimiento').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        time: false, // No mostrar selector de hora
        lang: 'es',
        weekStart: 1
    });

    // --- Lógica para cargar provincias dinámicamente ---
    $('#socio-pai_id').on('change', function() {
        var paiId = $(this).val();
        var provinciaDropdown = $('#socio-prv_id');
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
    if ($('#socio-pai_id').val()) {
        $('#socio-pai_id').trigger('change');
    }
});
JS;

$this->registerJs($js);
?>

<div class="page-content">
    <h6 class="mb-0 text-uppercase">Editar Socio <dl>* Datos obligatorios</dl></h6>
    <hr/>
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body p-5">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form',
                            'id' => 'sociosForm',
                            'enctype' => 'multipart/form-data'
                        ]
                    ]); ?>
                    <div id="respuestaForm">
                        <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_numero_original')->hiddenInput()->label(false) ?>
                                <?= $form->field($model, 'soc_numero', [
                                    'template' => "<label>Número Socio*</label>\n{input}\n{hint}\n{error}",
                                    'inputOptions' => ['class' => 'form-control mb-3', 'placeholder' => 'xxxxxx', 'required' => true]
                                ])->textInput() ?>
                            </div>
                            <div class="card-title d-flex align-items-center">
                                <h5 class="mb-0 text-white">DATOS PERSONALES</h5>
                            </div>
                            <hr>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_fecha', [
                                        'template' => "<label>Fecha de alta *</label>\n{input}\n{hint}\n{error}",
                                    ])->textInput(['class' => 'result form-control mb-3', 'placeholder' => 'Fecha', 'required' => true]) // Quitado type=date 
                                ?>
                            </div>
                            <div class="col-md-8"><!-- Vacío para generar salto --></div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_nombre', [
                                        'template' => "<label>Nombre *</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_apellido1', [
                                        'template' => "<label>Primer Apellido *</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_apellido2', [
                                        'template' => "<label>Segundo Apellido *</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_sexo', [
                                        'template' => "<label>Sexo *</label>\n{input}\n{hint}\n{error}"
                                    ])->dropDownList(
                                            [
                                                'Femenino' => 'Femenino',
                                                'Masculino' => 'Masculino',
                                            ],
                                            [
                                                'prompt' => 'Seleccione',
                                                'class' => 'form-control mb-3',
                                                'required' => true,
                                            ]
                                    ) ?>
                            </div>
                            <div class="col-md-4">                    
                                <?= $form->field($model, 'tdo_id', [
                                        'template' => "<label>Tipo Documento *</label>\n{input}\n{hint}\n{error}"
                                    ])->dropDownList(
                                        ArrayHelper::map(TipoDocIdentidad::find()->all(), 'tdo_id', 'tdo_nombre'),
                                        ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true]
                                    ) ?>
                            </div>
                            <div class="col-md-4">
                                 <?= $form->field($model, 'soc_numdocide', [
                                        'template' => "<label>Número Documento *</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'cat_id', [
                                        'template' => "<label>Categoría</label>\n{input}\n{hint}\n{error}"
                                    ])->dropDownList(
                                        $categories, 
                                        ['prompt' => 'Seleccione', 'class' => 'form-control mb-3']
                                    ) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_ocupacion', [
                                        'template' => "<label>Ocupación</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3']
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_fecnacimiento', [
                                        'template' => "<label>Fecha de nacimiento *</label>\n{input}\n{hint}\n{error}",
                                    ])->textInput(['class' => 'result form-control mb-3', 'placeholder' => 'Fecha', 'required' => true]) // Quitado type=date 
                                ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="card-title d-flex align-items-center mt-3">
                                <h5 class="mb-0 text-white">PERFIL DETALLADO DEL SOCIO</h5>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <?= $form->field($model, 'soc_perfil', [
                                        'template' => "{input}\n{hint}\n{error}",
                                     ])->textarea(['rows' => 6, 'class' => 'form-control'])
                                ?>
                            </div>
                            <hr>
                            <div class="col-md-6">
                                <?= $form->field($model, 'soc_foto', [
                                            'template' => "<label class='form-label'>Foto de perfil</label>\n{input}\n{hint}\n{error}"
                                    ])->fileInput(['class' => 'form-control mb-3']) ?>
                                <?= $model->soc_foto?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="card-title d-flex align-items-center mt-3">
                                <h5 class="mb-0 text-white">DATOS DE CONTACTO</h5>
                            </div>
                            <hr>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_telfijo', [
                                        'template' => "<label>Teléfono fijo</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3']
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_telmovil', [
                                        'template' => "<label>Móvil</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3']
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_direccion', [
                                        'template' => "<label>Domicilio</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3']
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'prv_id', [
                                        'template' => "<label>Provincia *</label>\n{input}\n{hint}\n{error}"
                                    ])->dropDownList(
                                        $provinces, 
                                        ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true]
                                    ) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_poblacion', [
                                        'template' => "<label>Población</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3']
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_codpostal', [
                                        'template' => "<label>Código postal</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3']
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_email', [
                                        'template' => "<label>E-mail *</label>\n{input}\n{hint}\n{error}"
                                    ])->input('email', [
                                            'class' => 'form-control mb-3',
                                            'required' => true,
                                    ]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_web', [
                                        'template' => "<label>Web</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3']
                                    ])->textInput() ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="card-title d-flex align-items-center mt-3">
                                <h5 class="mb-0 text-white">DATOS DE FACTURACIÓN</h5>
                            </div>
                            <hr>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_numsegsocial', [
                                        'template' => "<label>Número seguridad social *</label>\n{input}\n{hint}\n{error}",
                                    ])->textInput([
                                        'class' => 'form-control mb-3',
                                        'required' => true,
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_grcotsegsocial', [
                                        'template' => "<label>Grupo cotización seguridad social</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3']
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_coefcotizacion', [
                                        'template' => "<label>Coeficiente de cotización</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3']
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_basecotizacion', [
                                        'template' => "<label>Base de cotización</label>\n{input}\n{hint}\n{error}",
                                        'inputOptions' => ['class' => 'form-control mb-3']
                                    ])->textInput() ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_ctabancaria', [
                                        'template' => "<label>Cuenta bancaria *</label>\n{input}\n{hint}\n{error}",
                                    ])->textInput([
                                        'class' => 'form-control mb-3',
                                        'required' => true,
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_porcretirpf', [
                                        'template' => "<label>Porcentaje IRPF *</label>\n{input}\n{hint}\n{error}",
                                    ])->textInput([
                                        'class' => 'form-control mb-3',
                                        'required' => true,
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'soc_deuda', [
                                        'template' => "<label>Deuda</label>\n{input}\n{hint}\n{error}",
                                    ])->textInput([
                                        'class' => 'form-control mb-3',
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <label class="d-block">Estado</label>
                                <div class="form-check form-switch">
                                    <?= $form->field($model, 'soc_estado', [
                                            'template' => "{input}\n{error}",
                                        ])->checkbox([
                                            'value' => 'Activo',
                                            'uncheck' => 'Inactivo', 
                                            'class' => 'form-check-input me-2',
                                            'checked' => "true",
                                    ], false) ?>
                                    <label class="form-check-label" for="abble1">Activado</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex">
                                    <?= $form->field($model, 'soc_pago_participacion', [
                                            'template' => "{input}\n{error}",
                                        ])->checkbox([
                                            'value' => 1,
                                            'uncheck' => 0, 
                                            'class' => 'form-check-input me-2',
                                    ], false) ?>
                                    Cotejo del pago de las participaciones
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="card-title d-flex align-items-center mt-3">
                                <h5 class="mb-0 text-white">DOCUMENTOS</h5>
                            </div>
                            <hr>
                            <div class="col-md-6">
                                <?= $form->field($model, 'soc_ficlogo', [
                                            'template' => "<label class='form-label'>Logo</label>\n{input}\n{hint}\n{error}"
                                    ])->fileInput(['class' => 'form-control mb-3']) ?>
                                <?= $model->soc_ficlogo?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'soc_ficcontrato', [
                                            'template' => "<label class='form-label'>Contrato</label>\n{input}\n{hint}\n{error}"
                                    ])->fileInput(['class' => 'form-control mb-3']) ?>
                                <?= $model->soc_ficcontrato?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'soc_ficdocide', [
                                            'template' => "<label class='form-label'>Documento identidad</label>\n{input}\n{hint}\n{error}"
                                    ])->fileInput(['class' => 'form-control mb-3']) ?>
                                <?= $model->soc_ficdocide?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'soc_ficotros', [
                                            'template' => "<label class='form-label'>Otros Documentos</label>\n{input}\n{hint}\n{error}"
                                    ])->fileInput(['class' => 'form-control mb-3']) ?>
                                <?= $model->soc_ficotros?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'soc_fiprl', [
                                            'template' => "<label class='form-label'>PRL</label>\n{input}\n{hint}\n{error}"
                                    ])->fileInput(['class' => 'form-control mb-3']) ?>
                                <?= $model->soc_fiprl?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="card-title d-flex align-items-center mt-3">
                                <h5 class="mb-0 text-white">OBSERVACIONES</h5>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <?= $form->field($model, 'soc_observaciones', [
                                        'template' => "{input}\n{hint}\n{error}",
                                     ])->textarea(['rows' => 4, 'class' => 'form-control'])
                                ?>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success px-5 radius-30']) ?>
                        </div>
                    </div>
                    <!--{ !! Form::close() !!} -->
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    /* Registrar el archivo JS de CKEditor si no está cargado */
    $this->registerJsFile(
        '@web/assets-custom/plugins/ckeditor.js', // ruta donde tengas CKEditor
        ['position' => View::POS_HEAD]
    );

    /* Inicializar el editor */
    $this->registerJs("
        ClassicEditor
            .create(document.querySelector('#socio-soc_perfil'))
            .catch(error => {
                console.error(error);
            });
    ", View::POS_READY);

    /* Inicializar el editor */
    $this->registerJs("
        ClassicEditor
            .create(document.querySelector('#socio-soc_observaciones'))
            .catch(error => {
                console.error(error);
            });
    ", View::POS_READY);
?>