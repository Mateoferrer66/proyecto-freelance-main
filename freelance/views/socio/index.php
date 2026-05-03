<?php

use app\models\Socio;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;
use yii\grid\CheckboxColumn;
use yii\web\View;

/** @var yii\web\View $this */
/** @var app\models\ClienteSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Socios';
$this->params['breadcrumbs'] = [];
$this->registerCss(".table thead a { text-decoration: none !important; }");

foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
    echo \yii\bootstrap5\Alert::widget([
        'options' => ['class' => 'alert-' . $type],
        'body' => $message,
    ]);
}

// JS for search
$this->registerJs(<<<JS
    // --- Inicialización del Datepicker ---
    $('#sociosearch-fecha_inicial').bootstrapMaterialDatePicker({
        format: 'DD/MM/YYYY',
        time: false, // No mostrar selector de hora
        lang: 'es',
        weekStart: 1
    });
    $('#sociosearch-fecha_final').bootstrapMaterialDatePicker({
        format: 'DD/MM/YYYY',
        time: false, // No mostrar selector de hora
        lang: 'es',
        weekStart: 1
    });
    $('#estado-fecha').bootstrapMaterialDatePicker({
        format: 'DD/MM/YYYY',
        time: false, // No mostrar selector de hora
        lang: 'es',
        weekStart: 1
    });
JS);

// JS for batch delete
$batchDeleteUrl = Url::to(['socio/batch-delete']);
$this->registerJs(<<<JS
$('#batch-delete-button').on('click', function() {
    var keys = $('#socios-grid-view').yiiGridView('getSelectedRows');
    if (keys.length === 0) {
        alert('Debe seleccionar al menos un socio para eliminar.');
        return;
    }

    showConfirm(
        '¿Está seguro de que desea eliminar los ' + keys.length + ' socios seleccionados?',
        function() {
            \$.ajax({
                url: '$batchDeleteUrl',
                type: 'post',
                data: { ids: keys },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showToast(response.message);
                        \$.pjax.reload({container: '#socios-pjax'});
                    } else {
                        showToast('Error: ' + response.message, 'error');
                    }
                },
                error: function() {
                    showToast('Ocurrió un error al procesar la solicitud.', 'error');
                }
            });
        }
    );
});
JS);
?>
<div class="page-content">

    <div class="col d-flex justify-content-between align-items-start">
        <h6 class="mb-0 text-uppercase">Socios en lista <dl><?= $dataProvider->getTotalCount() ?></dl>
        </h6>
        <div>
            <?= Html::a('<i class="bx bx-plus mr-1"></i> Crear Socio', ['create'], [
                'class' => 'btn btn-success radius-30',
                'title' => 'Crear Socio',
            ]) ?>
        </div>
    </div>
    <hr />
    <div class="card">
        <div class="card-body">
            <?php Pjax::begin(['id' => 'socios-pjax', 'timeout' => 5000, 'enablePushState' => false, 'linkSelector' => '#socios-pjax .grid-view a']); ?>
            <?php $form = \yii\widgets\ActiveForm::begin([
                                'id' => 'search-form',
                                'method' => 'get',
                                'action' => ['socio/index'],
                                'options' => [
                                    'class' => 'mb-4',
                                    'data-pjax' => true,
                                ],
            ]); ?>
                <div class="row">
                    <div class="col-md-2">
                        <?= $form->field($searchModel, 'fecha_inicial', [
                                    'options' => ['class' => 'mb-0']
                            ])->textInput([
                                    'class' => 'result form-control',
                                    'placeholder' => 'Fecha inicial',
                            ])->label(false) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($searchModel, 'fecha_final', [
                                    'options' => ['class' => 'mb-0']
                            ])->textInput([
                                    'class' => 'result form-control',
                                    'placeholder' => 'Fecha inicial',
                            ])->label(false) ?>
                    </div>
                    <div class="col-md-1">
                        <?= Html::submitButton('<i class="bx bx-search-alt"></i>', [
                                'class' => 'btn btn-success radius-30',
                                'form' => 'search-form',  // hace referencia al id del ActiveForm
                        ]) ?>
                    </div>
                    <div class="col-md-7 d-flex flex-column align-items-end gap-2">
                        <div class="d-flex gap-2">
                            <?= Html::button('<i class="bx bx-trash"></i> Eliminar', [
                                'class' => 'btn text-orange radius-30',
                                'id' => 'batch-delete-button',
                            ]) ?>
                            <a href="<?= Url::to(['socio/members-status-report']) ?>" class="btn text-orange radius-30"><i class="bx bx-list-ol mr-1"></i> Altas/Bajas</a>
                            <a href="<?= Url::to(['socio/members-report']) ?>" class="btn text-orange radius-30"><i class="bx bx-list-ol mr-1"></i> Listado Socios</a>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="mb-0 text-white">Buscar:</label>
                            <?= $form->field($searchModel, 'buscar', ['template' => '{input}'])
                                    ->textInput([
                                        'placeholder' => '',
                                        'class' => 'form-control form-control-sm',
                                        'id' => 'socio-search-input',
                                        'autocomplete' => 'off',
                                        'style' => 'background-color: transparent; border: 1px solid #484848; color: white;'
                                    ]) ?>
                        </div>
                    </div>
                </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
            <div class="table-responsive">
                <?= GridView::widget([
                    'id' => 'socios-grid-view',
                    'dataProvider' => $dataProvider,
                    'summary' => false,
                    'tableOptions' => ['class' => 'tableData table mb-0 dataTable no-footer'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'headerOptions' => [
                                'style' => 'width: 14px; text-align: center;'
                            ],
                            'checkboxOptions' => [
                                'class' => 'form-check-input'
                            ],
                        ],
                        [
                            'attribute' => 'soc_foto',
                            'label' => 'Foto',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->soc_foto) {
                                    return Html::img(
                                        Yii::getAlias('@web/uploads/members/photo/' . $model->soc_foto),
                                        [
                                            'width' => '55px',
                                            'height' => '55px',
                                            'class' => 'rounded-circle'
                                        ]
                                    );
                                }

                                return Html::img(
                                    Yii::getAlias('@web/assets-custom/images/no-image.jpg'),
                                        [
                                            'width' => '55px',
                                            'height' => '55px',
                                            'class' => 'rounded-circle'
                                        ]
                                );
                            },
                        ],
                        [
                            'attribute' => 'soc_numero',
                            'headerOptions' => [
                                'class' => 'sorting',
                                'style' => 'width: 50.2167px; text-align: center;'
                            ],
                            'label' => 'Código',
                            'encodeLabel' => false, // Allow HTML
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->soc_numero;
                            }
                        ],
                        [
                            'attribute' => 'soc_nombre',
                            'headerOptions' => [
                                'class' => 'sorting',
                                'style' => 'width: 66.3167px; text-align: center;'
                            ],
                            'label' => 'Nombre',
                            'encodeLabel' => false,
                            'format' => 'raw',
                            'contentOptions' => function ($model, $key, $index, $column) {
                                $class = $model->soc_estado === Socio::SOC_ESTADO_ACTIVO ? 'greenGdt' : 'redGdt';
                                return ['class' => $class];
                            },
                            'value' => function ($model) {
                                return '<font class="text-white">' . $model->soc_nombre . '</font>';
                            }
                        ],
                        [
                            'attribute' => 'soc_apellido1',
                            'headerOptions' => [
                                'class' => 'sorting',
                                'style' => 'width: 61.7167px; text-align: center;'
                            ],
                            'label' => 'Apellido 1',
                            'encodeLabel' => false, // Allow HTML
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->soc_apellido1;
                            }
                        ],
                        [
                            'attribute' => 'soc_apellido2',
                            'headerOptions' => [
                                'class' => 'sorting',
                                'style' => 'width: 61.7167px; text-align: center;'
                            ],
                            'label' => 'Apellido 2',
                            'encodeLabel' => false, // Allow HTML
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->soc_apellido2;
                            }
                        ],
                        [
                            'attribute' => 'soc_apellido',
                            'headerOptions' => [
                                'class' => 'sorting',
                                'style' => 'width: 73.65px; text-align: center;'
                            ],
                            'label' => 'Apellidos',
                            'encodeLabel' => false, // Allow HTML
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->soc_apellido;
                            }
                        ],
                        [
                            'headerOptions' => [
                                'class' => 'sorting',
                                'style' => 'width: 102.75px; text-align: center;'
                            ],
                            'label' => 'Categoría',
                            'value' => function($model) {
                                return $model->categoria->cat_nombre ?? '-';
                            }
                        ],
                        [
                            'attribute' => 'soc_estado',
                            'headerOptions' => [
                                'class' => 'sorting',
                                'style' => 'width: 56.6333px; text-align: center;'
                            ],
                            'label' => 'Estado',
                            'encodeLabel' => false,
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->soc_estado === Socio::SOC_ESTADO_ACTIVO) {
                                    return '<i class="bx bx-radio-circle-marked bx-burst align-middle font-18 me-1 text-success"></i><span class="text-white">' . $model->soc_estado . '</span>';
                                } else {
                                    return '<i class="bx bx-radio-circle-marked align-middle font-18 me-1 text-danger"></i><span class="text-white">' . $model->soc_estado . '</span>';
                                }
                            },
                        ],
                        [
                            'class' => ActionColumn::class,
                            'header' => Html::a('Acciones', ['index', 'sort' => 'soc_nombre'], ['data-pjax' => 1]),
                            'template' => '<div class="action-grid-2x2">{toggle} {view} {update} {contract} {mail} {delete}</div>',
                            'headerOptions' => ['class' => 'text-start', 'style' => 'width: 1%; white-space: nowrap;'], // Shrink-wrap & Left align
                            'contentOptions' => ['class' => 'text-end', 'style' => 'width: 1%; white-space: nowrap;'], // Shrink-wrap & Right align content
                            'buttons' => [
                                'toggle' => fn($url, $model) => Html::button(
                                    $model->soc_estado === 'Activo'
                                        ? '<i class="bx bx-power-off"></i>'
                                        : '<i class="bx bx-play-circle"></i>',
                                    [
                                        'class'        => 'btn btn-light btnCambiarEstado',
                                        'data-id'      => $model->soc_id,
                                        'data-estado'  => $model->soc_estado,
                                        'title'        => $model->soc_estado === 'Activo' ? 'Desactivar Socio' : 'Activar Socio',
                                    ]
                                ),
                                'view' => fn($url, $model) => Html::button('<i class="bx bx-show"></i>', [
                                    'class'    => 'btn btn-light btnVerSocio',
                                    'data-id'  => $model->soc_id,   
                                    'title'    => 'Ver Socio',
                                ]),
                                'update' => fn($url, $model) => Html::a('<i class="bx bx-edit"></i>', $url, [
                                    'title' => 'Editar Socio: ' . $model->soc_nombre,
                                    'class' => 'btn btn-light',
                                    'data-pjax' => '0',
                                ]),
                                'contract' => fn($url, $model) => Html::a('<i class="bx bx-file"></i>', ['socio/contract', 'id' => $model->id], [
                                    'title' => 'Ver contrato: ' . $model->soc_nombre,
                                    'class' => 'btn btn-light',
                                    'target' => '_blank',
                                    'data-pjax'  => '0',
                                ]),
                                'mail' => function ($url, $model) {
                                    return Html::button('<i class="bx bx-envelope"></i>', [
                                        'title' => 'Enviar correo a ' . $model->soc_nombre,
                                        'class' => 'btn btn-light btnMail',
                                        'data-id' => $model->soc_id,
                                        'data-email' => $model->soc_email,
                                        'data-name' => $model->soc_nombre,
                                        'data-number' => $model->soc_numero,
                                    ]);
                                },
                                'delete' => fn($url, $model) => Html::a('<i class="bx bx-trash"></i>', $url, [
                                    'title' => 'Eliminar Socio',
                                    'class' => 'btn btn-light',
                                    'data-confirm' => '¿Está seguro de que desea eliminar el socio: "' . $model->soc_nombre . '"?',
                                    'data-method' => 'post',
                                    'data-pjax' => '0',
                                ]),
                            ],
                            'urlCreator' => fn($action, Socio $model, $key, $index, $column) =>
                            Url::toRoute([$action, 'soc_id' => $model->soc_id, 'view' => ($action === 'delete' || $action === 'toggle' ? null : 'modal')]),
                        ],
                    ],
                ]); ?>
            </div>
        </div>
        <?php Pjax::end(); ?>
    </div>
</div>
<!-- Modal mail socio -->
<div class="modal fade" id="socioMailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar Correo a Socio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" accept-charset="UTF-8" class="form" id="sociosFormMail" name="sociosFormMail" enctype="multipart/form-data">
                    <input type="hidden" name="member" id="member">
                    <input type="hidden" name="memberNumber" id="memberNumber">
                    <div id="">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Destinatario</label>
                                <input id="recipient" class="form-control mb-3" name="recipient" type="text">
                            </div>
                            <div class="col-md-12">
                                <label>CC</label>
                                <input id="cc" class="form-control mb-3" name="cc" type="text">
                            </div>
                            <div class="col-md-12">
                                <label>Asunto</label>
                                <input id="subject" class="form-control mb-3" name="subject" type="text" value="Bienvenido a Freelance SCM">
                            </div>
                            <div class="col-md-12">
                                <label for="attachment" class="form-label">Adjuntar</label>
                                <input class="form-control mb-3" id="attachment" type="file">
                            </div>
                            <div class="card-title d-flex align-items-center mt-3">
                                <h5 class="mb-0 text-white">MENSAJE</h5>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <textarea class="form-control" id="editor3" rows="4"></textarea>
                                <input type="hidden" name="message" id="message">
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <input class="btn btn-success px-5 radius-30" type="submit" id="btnEnviarEmail" value="Enviar">
                        </div>
                        <!--{ !! Form::close() !!} -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal ver socio -->
<div class="modal fade" id="socioVerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ver Socio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- El contenido se inyecta aquí desde el servidor -->
            </div>
        </div>
    </div>
</div>
<!-- Modal activar/desactivar socio -->
<div class="modal fade" id="socioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="socioModalTitulo">Cambiar Estado Socio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="socioEstadoForm">
                    <input type="hidden" id="estado-soc-id">
                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control" type="text" id="estado-fecha" placeholder="Fecha">
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-0 text-uppercase">
                                Nuevo estado<br>
                                <span id="estado-nuevo-label" class="badge"></span>
                            </h6>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label>Observaciones</label>
                            <textarea class="form-control" id="editor-estado"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnGuardarEstado" class="btn btn-success px-5 radius-30">Guardar</button>
            </div>
        </div>
    </div>
</div>
<?php
$urlGetSocio    = \yii\helpers\Url::toRoute(['socio/get-member']);
$urlEnviarEmail = \yii\helpers\Url::toRoute(['socio/send-email']);
$urlGetTemplate = \yii\helpers\Url::toRoute(['socio/get-email-template']);
$urlToggleStatus = \yii\helpers\Url::toRoute(['socio/toggle-status']);

/* Registrar el archivo JS de CKEditor si no está cargado */
$this->registerJsFile(
    '@web/assets-custom/plugins/ckeditor.js', // ruta donde tengas CKEditor
    ['position' => View::POS_HEAD]
);

$this->registerCss("
    .ck-editor__editable {
        background-color: #1a1a1a !important;
        color: #ffffff !important;
        min-height: 200px;
    }
    .ck-editor__editable p,
    .ck-editor__editable a,
    .ck-editor__editable li {
        color: #ffffff !important;
    }
    .ck.ck-editor__main > .ck-editor__editable:not(.ck-focused) {
        background-color: #1a1a1a !important;
    }
");

$this->registerJs(<<<JS
    let editor = null;
    let editorStatus = null;
    let pendingContent = null;

    \$('#socioMailModal').on('shown.bs.modal', function () {
        if (!editor) {
            ClassicEditor.create(document.querySelector('#editor3'))
                .then(e => {
                    editor = e;
                    // Si ya llegó el contenido del AJAX antes de que el editor cargara
                    if (pendingContent !== null) {
                        editor.setData(pendingContent);
                        pendingContent = null;
                    }
                })
                .catch(error => console.error(error));
        } else {
            // Editor ya existe, aplicar contenido pendiente si hay
            if (pendingContent !== null) {
                editor.setData(pendingContent);
                pendingContent = null;
            }
        }
    });

    \$(document).on('click', '.btnMail', function() {
        let email  = \$(this).data('email');
        let nombre = \$(this).data('name');
        let id = \$(this).data('id');
        let socnumero = \$(this).data('number');

        \$('#recipient').val(email);
        \$('#member').val(id);
        \$('#memberNumber').val(socnumero);
        pendingContent = null; // limpiar contenido anterior

        // Deshabilitar botón enviar mientras carga
        \$('#btnEnviarEmail').prop('disabled', true).text('Cargando...');

        \$('#socioMailModal').modal('show');

        \$.post('$urlGetTemplate', { socnumero: socnumero }, function(response) {
            if (response.success) {
                if (editor) {
                    editor.setData(response.html);
                } else {
                    // El editor aún no terminó de cargar, guardar para después
                    pendingContent = response.html;
                }
            }
        }).always(function() {
            // Rehabilitar botón siempre, haya éxito o error
            \$('#btnEnviarEmail').prop('disabled', false).text('Enviar');
        });
    });

    function validateAttachment(file) {
        if (!file) return true;
        const maxSize = 5 * 1024 * 1024;
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (file.size > maxSize) { showToast('El archivo supera 5MB', 'error'); return false; }
        if (!allowedTypes.includes(file.type)) { showToast('Tipo de archivo no permitido', 'error'); return false; }
        return true;
    }

    \$(document).on('submit', '#sociosFormMail', function(e) {
        e.preventDefault();
        if (!editor) { 
            showToast('El editor no está cargado', 'error'); 
            return; 
        }

        const recipient = \$('#recipient').val().trim();
        const subject = \$('#subject').val().trim();
        const body = editor.getData().trim();

        if (!recipient) { 
            showToast('El destinatario es requerido', 'error'); 
            return; 
        }

        if (!subject) { 
            showToast('El asunto es requerido', 'error'); 
            return; 
        }

        if (!body) { 
            showToast('El mensaje no puede estar vacío', 'error'); 
            return; 
        }

        \$('#message').val(body);
        let file = \$('#attachment')[0].files[0];
        if (!validateAttachment(file)) {
            return;
        }
        let formData = new FormData(this);
        \$.ajax({
            url: '$urlEnviarEmail',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    showToast('Email enviado correctamente');
                    \$('#socioMailModal').modal('hide');
                    \$('#sociosFormMail')[0].reset();
                    editor.setData('');
                } else {
                    showToast('Error: ' + (response.error || 'No se pudo enviar el email'), 'error');
                }
            },
            error: function(xhr) {
                console.log(xhr.status, xhr.responseText);
                showToast('Error del servidor', 'error');
            }
        });
    });

    \$('#socioMailModal').on('hidden.bs.modal', function () {
        \$('#sociosFormMail')[0].reset();
        if (editor) {
            editor.destroy()
                .then(() => { editor = null; })
                .catch(error => console.error(error));
        }
    });

    \$(document).on('click', '.btnVerSocio', function() {
        let id = \$(this).data('id');
        \$.get('$urlGetSocio', { id: id }, function(response) {
            if (response.success) {
                \$('#socioVerModal .modal-body').html(response.html);
                \$('#socioVerModal').modal('show');
            } else {
                showToast(response.error, 'error');
            }
        });
    });

    // Abrir modal
    \$(document).on('click', '.btnCambiarEstado', function() {
        let id     = \$(this).data('id');
        let estado = \$(this).data('estado');
        let esActivo = estado === 'Activo';

        \$('#estado-soc-id').val(id);
        \$('#socioModalTitulo').text(esActivo ? 'Desactivar Socio' : 'Activar Socio');
        \$('#estado-nuevo-label')
            .text(esActivo ? 'Inactivo' : 'Activo')
            .removeClass('bg-success bg-danger')
            .addClass(esActivo ? 'bg-danger' : 'bg-success');
        \$('#socioEstadoForm')[0].reset();
        \$('#socioModal').modal('show');
    });

    // Inicializar CKEditor al abrir
    \$('#socioModal').on('shown.bs.modal', function() {
        if (!editorStatus) {
            ClassicEditor.create(document.querySelector('#editor-estado'))
                .then(e => { editorStatus = e; })
                .catch(error => console.error(error));
        } else {
            editorStatus.setData('');
        }
    });

    // Destruir editor al cerrar
    \$('#socioModal').on('hidden.bs.modal', function() {
        \$('#socioEstadoForm')[0].reset();
        if (editorStatus) {
            editorStatus.destroy()
                .then(() => { editorStatus = null; })
                .catch(error => console.error(error));
        }
    });

    // Guardar
    \$('#btnGuardarEstado').on('click', function() {
        if (!editorStatus) { showToast('El editor no está cargado', 'error'); return; }

        const socId        = \$('#estado-soc-id').val();
        const fecha        = \$('#estado-fecha').val();
        const observaciones = editorStatus.getData();

        if (!fecha)         { showToast('La fecha es requerida', 'error'); return; }
        if (!observaciones) { showToast('Las observaciones son requeridas', 'error'); return; }

        \$.ajax({
            url: '$urlToggleStatus',
            type: 'POST',
            data: {
                soc_id: socId,
                sab_fecha: fecha,
                sab_observaciones: observaciones,
                _csrf: yii.getCsrfToken()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showToast('Estado cambiado a ' + response.nuevo_estado + ' correctamente');
                    \$('#socioModal').modal('hide');
                    \$.pjax.reload({ container: '#socios-pjax' });
                } else {
                    showToast('Error: ' + response.error, 'error');
                }
            },
            error: function() {
                showToast('Error del servidor', 'error');
            }
        });
    });
JS, \yii\web\View::POS_READY);
?>