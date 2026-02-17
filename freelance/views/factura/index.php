<?php

use app\models\Factura;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;
use yii\grid\CheckboxColumn;

/** @var yii\web\View $this */
/** @var app\models\FacturaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Facturación';
$this->params['breadcrumbs'] = [];
$this->registerCss(".table thead a { text-decoration: none !important; }");



// JS for batch delete
$this->registerJs(<<<'JS'
$('#batch-delete-button').on('click', function() {
    var keys = $('#facturas-grid-view').yiiGridView('getSelectedRows');
    if (keys.length === 0) {
        alert('Debe seleccionar al menos una factura para eliminar.');
        return;
    }

    if (confirm('¿Está seguro de que desea eliminar las ' + keys.length + ' facturas seleccionadas?')) {
        $.ajax({
            url: 'index.php?r=factura/batch-delete',
            type: 'post',
            data: { ids: keys },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $.pjax.reload({container: '#facturas-pjax'});
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Ocurrió un error al procesar la solicitud.');
            }
        });
    }
});
JS);

// JS for date pickers
$this->registerJs(<<<'JS'
$(function() {
    $('#fecha-inicial, #fecha-final').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        time: false,
        lang: 'es',
        weekStart: 1
    });
});
JS);

// CSS to reduce margin for form-group in search form
$this->registerCss(<<<'CSS'
#auto-search-form .form-group {
    margin-bottom: 0.5rem; /* Adjust as needed */
}
CSS);


?>

<?php
Modal::begin([
    'id' => 'action-modal',
    'title' => '',
    'size' => 'modal-lg',
    'footer' => '',
]);
echo "<div id='modal-content'><div class='text-center'><div class='spinner-border' role='status'></div></div></div>";
Modal::end();

// JS for modal
$this->registerJs(<<<'JS'
let actionModalInstance = new bootstrap.Modal(document.getElementById('action-modal'));

// Handler para abrir el modal
$(document).on('click', '[data-bs-toggle="modal"]', function(e) {
    e.preventDefault();
    const modalTitle = $('#action-modal .modal-title');
    const modalContent = $('#modal-content');
    const url = $(this).attr('href') || $(this).data('url'); // Soporte para data-url en el botón de crear
    const title = $(this).attr('title');

    modalTitle.text(title);
    modalContent.html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
    actionModalInstance.show();

    $.get(url)
        .done(function(data) {
            modalContent.html(data);
        })
        .fail(function() {
            modalContent.html('<div class="alert alert-danger">Error al cargar el contenido.</div>');
        });
});

// Limpiar modal al cerrar
document.getElementById('action-modal').addEventListener('hidden.bs.modal', function () {
    const modalContent = $('#modal-content');
    if (modalContent.find('form').length > 0) {
        modalContent.find('form').yiiActiveForm('destroy');
    }
    modalContent.html('');
    $('#action-modal .modal-title').text('');
});

// Handler para submit de formularios en el modal con beforeSubmit de Yii2
$(document).on('beforeSubmit', '#modal-content form', function(e) {
    e.preventDefault();
    var form = $(this);
    var submitButton = form.find('button[type="submit"]');
    submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...');

    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                actionModalInstance.hide();
                $.pjax.reload({container: '#facturas-pjax', async: false}); // async: false para esperar a que pjax termine
            } else {
                // Muestra los errores de validación en el formulario
                form.yiiActiveForm('updateMessages', response.errors, true);
            }
        },
        error: function() {
            alert('Ocurrió un error al procesar la solicitud. Por favor, inténtelo de nuevo.');
        },
        complete: function() {
            submitButton.prop('disabled', false).html(form.find('button[type="submit"]').text().includes('Crear') ? 'Crear' : 'Actualizar');
        }
    });

    return false; // Previene el envío tradicional
});

// Event delegation for toggle approval button (works for index and modal)
$(document).on('click', '.toggle-approval-btn', function(e) {
    e.preventDefault();
    var btn = $(this);
    var url = btn.data('url');
    var icon = btn.find('i');
    
    // Disable button to prevent multiple clicks
    btn.prop('disabled', true);
    
    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Update icon and title based on new state
                if (response.nuevo_estado == 1) {
                    icon.removeClass('bx-check-shield').addClass('bx-shield-quarter'); 
                    btn.attr('title', 'Desaprobar');
                    // Reload pjax to update the grid if we are in index
                    if ($('#facturas-pjax').length > 0) {
                        $.pjax.reload({container: '#facturas-pjax', async: false});
                    }
                    // If inside modal, maybe show a success message or update button style
                    if (btn.closest('.modal').length > 0) {
                         btn.removeClass('btn-light').addClass('btn-success'); // Visual feedback
                         setTimeout(function() { btn.removeClass('btn-success').addClass('btn-light'); }, 1000);
                    }
                } else {
                    icon.removeClass('bx-shield-quarter').addClass('bx-check-shield');
                    btn.attr('title', 'Aprobar');
                    if ($('#facturas-pjax').length > 0) {
                        $.pjax.reload({container: '#facturas-pjax', async: false});
                    }
                }
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Ocurrió un error al procesar la solicitud.');
        },
        complete: function() {
            btn.prop('disabled', false);
        }
    });
});
JS);
?>
<div class="page-content">

    <div class="col d-flex justify-content-between align-items-start">
        <h6 class="mb-0 text-uppercase">Facturas <dl><?= $dataProvider->getTotalCount() ?></dl>
        </h6>
        <div>
            <?= Html::a('<i class="bx bx-plus mr-1"></i> Crear Factura', ['create'], [
                'class' => 'btn btn-success radius-30',
                'title' => 'Crear Factura',
            ]) ?>
        </div>
    </div>
    <hr />



    <div class="card">
        <div class="card-body">
            <?php Pjax::begin(['id' => 'facturas-pjax']); ?>
            <div class="col-xl-12 mx-auto">
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-4">
                                <?php $form = ActiveForm::begin([
                                    'action' => ['index'],
                                    'method' => 'get',
                                    'options' => [
                                        'class' => 'search-bar mb-3',
                                        'data-pjax' => 1,
                                        'id' => 'auto-search-form'
                                    ],
                                ]); ?>
                                <div class="row mb-1">
                                    <div class="col-md-3">
                                        <?= $form->field($searchModel, 'cli_nif', ['template' => '{input}'])->textInput(['placeholder' => 'NIF', 'class' => 'form-control']) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form->field($searchModel, 'cli_nombre', ['template' => '{input}'])->textInput(['placeholder' => 'Nombre Cliente', 'class' => 'form-control']) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form->field($searchModel, 'soc_numero', ['template' => '{input}'])->textInput(['placeholder' => 'Código Socio', 'class' => 'form-control']) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form->field($searchModel, 'soc_nombre', ['template' => '{input}'])->textInput(['placeholder' => 'Nombre Socio', 'class' => 'form-control']) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <?= $form->field($searchModel, 'fac_estado', ['template' => '{input}'])->dropDownList(
                                            Factura::optsFacEstado(),
                                            ['prompt' => 'Estado', 'class' => 'form-control']
                                        ) ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?= $form->field($searchModel, 'fecha_inicio', ['template' => '{input}'])->textInput(['placeholder' => 'Fecha inicial', 'class' => 'form-control datepicker', 'id' => 'fecha-inicial']) ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?= $form->field($searchModel, 'fecha_fin', ['template' => '{input}'])->textInput(['placeholder' => 'Fecha final', 'class' => 'form-control datepicker', 'id' => 'fecha-final']) ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?= Html::submitButton('<i class="bx bx-search-alt mr-1"></i>', ['class' => 'btn btn-success radius-30']) ?>
                                    </div>
                                    <div class="col-md-5 d-flex justify-content-end">
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="dt-buttons btn-group">
                                <?= Html::a('Excel', ['factura/export-excel'], [
                                    'target' => '_blank',
                                    'class' => 'btn btn-light buttons-excel buttons-html5',
                                    'data-pjax' => '0',
                                ]) ?>
                                <?= Html::a('PDF', ['factura/export-pdf'], [
                                    'target' => '_blank',
                                    'class' => 'btn btn-light buttons-excel buttons-html5',
                                    'data-pjax' => '0',
                                ]) ?>
                                <?= Html::a('Print', ['factura/print'], [
                                    'target' => '_blank',
                                    'class' => 'btn btn-light buttons-excel buttons-html5',
                                    'data-pjax' => '0',
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <?= Html::button('<i class="bx bx-trash"></i> ELIMINAR', [
                                'class' => 'btn text-orange radius-30',
                                'id' => 'batch-delete-button'
                            ]) ?>
                            <?= Html::a('<i class="bx bx-list-ol mr-1"></i>Listado Facturas', ['index'], ['class' => 'btn text-orange radius-30']) ?>
                        </div>
                    </div>



                        <div class="table-responsive">
                        <?= GridView::widget([
                            'id' => 'facturas-grid-view',
                            'dataProvider' => $dataProvider,
                            'summary' => false,
                            'tableOptions' => ['class' => 'tableData table mb-0 dataTable no-footer'],
                            'columns' => [
                                [
                                    'class' => 'yii\grid\CheckboxColumn',
                                    'checkboxOptions' => [
                                        'class' => 'form-check-input'
                                    ],
                                ],
                                [
                                    'attribute' => 'fac_numero',
                                    'label' => '# Factura',
                                ],
                             
                                [
                                    'attribute' => 'cli_id',
                                    'label' => 'Nombre / Razón Social',
                                    'value' => 'cli.cli_nombre',
                                ],
                                [
                                    'attribute' => 'fac_total',
                                    'label' => 'Importe',
                                    'format' => 'currency',
                                ],
                                   [
                                    'attribute' => 'fac_fecha',
                                    'label' => 'Fecha',
                                    'format' => ['date', 'php:d-m-Y'],
                                ],
                               
                                [
                                    'attribute' => 'fac_situacion',
                                    'label' => 'Situación',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        $icon = '';
                                        if ($model->fac_situacion === Factura::FAC_ESTADO_LIQUIDADA) {
                                            $icon = '<i class="bx bx-radio-circle-marked bx-burst align-middle font-18 me-1 text-success"></i>';
                                        } else {
                                            $icon = '<i class="bx bx-radio-circle-marked align-middle font-18 me-1 text-danger"></i>';
                                        }
                                        $date = $model->fac_fecha_situacion ? Yii::$app->formatter->asDate($model->fac_fecha_situacion, 'php:d-m-Y') : '';
                                        return $icon . $model->fac_situacion . '<br>' . $date;
                                    },
                                ],

                                [
                                    'attribute' => 'fac_estado',
                                    'label' => 'Estado',
                                ],
                                [
                                    'attribute' => 'soc_numero',
                                    'label' => 'Cód. Socio',
                                    'value' => 'soc.soc_numero',
                                ],
                                [
                                    'attribute' => 'fac_aprobada',
                                    'label' => 'Aprobacion',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->fac_aprobada 
                                            ? '<span class="badge bg-success">Aprobada</span>' 
                                            : '<span class="badge bg-warning text-dark">No Aprobada</span>';
                                    },
                                ],
                                [
                                    'class' => ActionColumn::class,
                                    'header' => 'Acciones',
                                    'template' => '<div class="d-flex flex-column align-items-center gap-2">{group1}{group2}{group3}</div>',
                                    'buttons' => [
                                        'group1' => function ($url, $model, $key) {
                                            $buttons = [];
                                            $buttons[] = Html::a('<i class="bx bx-id-card"></i>', Url::toRoute(['view', 'fac_id' => $model->fac_id, 'view' => 'modal']), [
                                                'title' => 'Ver Factura: ' . $model->fac_numero,
                                                'class' => 'btn btn-light',
                                                'data-bs-toggle' => 'modal',
                                                'data-bs-target' => '#action-modal'
                                            ]);
                                            $buttons[] = Html::a('<i class="bx bx-edit"></i>', Url::toRoute(['update', 'fac_id' => $model->fac_id, 'view' => 'modal']), [
                                                'title' => 'Editar Factura: ' . $model->fac_numero,
                                                'class' => 'btn btn-light',
                                                'data-bs-toggle' => 'modal',
                                                'data-bs-target' => '#action-modal'
                                            ]);
                                            $buttons[] = Html::a('<i class="bx bx-printer"></i>', Url::toRoute(['print', 'fac_id' => $model->fac_id]), [
                                                'title' => 'Imprimir Factura',
                                                'class' => 'btn btn-light',
                                                'target' => '_blank',
                                                'data-pjax' => '0',
                                            ]);
                                            return Html::tag('div', implode('', $buttons), ['class' => 'd-inline-flex gap-1']);
                                        },
                                        'group2' => function ($url, $model, $key) {
                                            $buttons = [];
                                            $buttons[] = Html::a('<i class="bx bx-envelope"></i>', Url::toRoute(['send-email', 'fac_id' => $model->fac_id]), [
                                                'title' => 'Enviar por Correo',
                                                'class' => 'btn btn-light',
                                                'data-bs-toggle' => 'modal',
                                                'data-bs-target' => '#action-modal',
                                            ]);
                                            $buttons[] = Html::a('<i class="bx bx-check-square"></i>', Url::toRoute(['mark-as-paid', 'fac_id' => $model->fac_id]), [
                                                'title' => 'Marcar como Liquidada',
                                                'class' => 'btn btn-light',
                                                'data-confirm' => '¿Está seguro de que desea marcar esta factura como liquidada?',
                                                'data-method' => 'post',
                                                'data-pjax' => '1',
                                            ]);
                                            $buttons[] = Html::a('<i class="bx bx-toggle-left"></i>', Url::toRoute(['change-status', 'fac_id' => $model->fac_id]), [
                                                'title' => 'Cambiar Situación',
                                                'class' => 'btn btn-light',
                                                'data-bs-toggle' => 'modal',
                                                'data-bs-target' => '#action-modal',
                                            ]);
                                            $buttons[] = Html::button('<i class="bx bx-check-shield"></i>', [
                                                'class' => 'btn btn-light toggle-approval-btn',
                                                'title' => $model->fac_aprobada ? 'Desaprobar' : 'Aprobar',
                                                'data-url' => Url::to(['toggle-aprobacion', 'fac_id' => $model->fac_id]),
                                                'onclick' => 'toggleApproval(this)',
                                            ]);
                                            return Html::tag('div', implode('', $buttons), ['class' => 'd-inline-flex gap-1']);
                                        },
                                        'group3' => function ($url, $model, $key) {
                                            $buttons = [];
                                            $buttons[] = Html::a('<i class="bx bx-power-off"></i>', Url::toRoute(['deactivate', 'fac_id' => $model->fac_id]), [
                                                'title' => 'Desactivar Factura',
                                                'class' => 'btn btn-light',
                                                'data-confirm' => '¿Está seguro de que desea desactivar la factura: "' . $model->fac_numero . '"?',
                                                'data-method' => 'post',
                                                'data-pjax' => '1',
                                            ]);
                                            $buttons[] = Html::a('<i class="bx bx-trash"></i>', Url::toRoute(['delete', 'fac_id' => $model->fac_id]), [
                                                'title' => 'Eliminar Factura',
                                                'class' => 'btn btn-light',
                                                'data-confirm' => '¿Está seguro de que desea eliminar la factura: "' . $model->fac_numero . '"?',
                                                'data-method' => 'post',
                                                'data-pjax' => '1',
                                            ]);
                                            return Html::tag('div', implode('', $buttons), ['class' => 'd-inline-flex gap-1']);
                                        },
                                    ],
                                    'urlCreator' => fn($action, Factura $model, $key, $index, $column) =>
                                    Url::toRoute([$action, 'fac_id' => $model->fac_id]),
                                ],
                            ],
                        ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php Pjax::end(); ?>
    </div>
</div>