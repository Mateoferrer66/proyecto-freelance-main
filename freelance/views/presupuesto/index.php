<?php

use app\models\Factura;
use app\models\Presupuesto;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;
use yii\grid\CheckboxColumn;



/** @var yii\web\View $this */
/** @var app\models\PresupuestoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Presupuestos';
$this->params['breadcrumbs'] = [];
$this->registerCss(".table thead a { text-decoration: none !important; }");



// JS for batch delete
$this->registerJs(<<<'JS'
$('#batch-delete-button').on('click', function() {
    var keys = $('#presupuestos-grid-view').yiiGridView('getSelectedRows');
    if (keys.length === 0) {
        alert('Debe seleccionar al menos un presupuesto para eliminar.');
        return;
    }

    if (confirm('¿Está seguro de que desea eliminar los ' + keys.length + ' presupuestos seleccionados?')) {
        $.ajax({
            url: 'index.php?r=presupuesto/batch-delete',
            type: 'post',
            data: { ids: keys },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $.pjax.reload({container: '#presupuestos-pjax'});
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
    var originalButtonHtml = submitButton.html();
    submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...');

    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                actionModalInstance.hide();
                $.pjax.reload({container: '#presupuestos-pjax', async: false}); // async: false para esperar a que pjax termine
            } else {
                // Muestra los errores de validación en el formulario
                form.yiiActiveForm('updateMessages', response.errors, true);
            }
        },
        error: function() {
            alert('Ocurrió un error al procesar la solicitud. Por favor, inténtelo de nuevo.');
        },
        complete: function() {
            submitButton.prop('disabled', false).html(originalButtonHtml);
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
                    if ($('#presupuestos-pjax').length > 0) {
                        $.pjax.reload({container: '#presupuestos-pjax', async: false});
                    }
                    // If inside modal, maybe show a success message or update button style
                    if (btn.closest('.modal').length > 0) {
                         btn.removeClass('btn-light').addClass('btn-success'); // Visual feedback
                         setTimeout(function() { btn.removeClass('btn-success').addClass('btn-light'); }, 1000);
                    }
                } else {
                    icon.removeClass('bx-shield-quarter').addClass('bx-check-shield');
                    btn.attr('title', 'Aprobar');
                    if ($('#presupuestos-pjax').length > 0) {
                        $.pjax.reload({container: '#presupuestos-pjax', async: false});
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
        <h6 class="mb-0 text-uppercase">Presupuestos <dl><?= $dataProvider->getTotalCount() ?></dl>
        </h6>
        <div>
            <?= Html::a('<i class="bx bx-plus mr-1"></i> Crear Presupuesto', ['create'], [
                'class' => 'btn btn-success radius-30',
                'title' => 'Crear Presupuesto',
            ]) ?>
        </div>
    </div>
    <hr />



    <div class="card">
        <div class="card-body">
            <?php Pjax::begin(['id' => 'presupuestos-pjax']); ?>
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
                                        <?= $form->field($searchModel, 'fecha_inicio', ['template' => '{input}'])->textInput(['placeholder' => 'Fecha inicial', 'class' => 'form-control datepicker', 'id' => 'fecha-inicial']) ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?= $form->field($searchModel, 'fecha_fin', ['template' => '{input}'])->textInput(['placeholder' => 'Fecha final', 'class' => 'form-control datepicker', 'id' => 'fecha-final']) ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?= Html::submitButton('<i class="bx bx-search-alt mr-1"></i>', ['class' => 'btn btn-success radius-30']) ?>
                                    </div>
                                    <div class="col-md-7 d-flex justify-content-end">
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="dt-buttons btn-group">
                                <?= Html::a('Excel', ['presupuesto/export-excel'], [
                                    'target' => '_blank',
                                    'class' => 'btn btn-light buttons-excel buttons-html5',
                                    'data-pjax' => '0',
                                ]) ?>
                                <?= Html::a('PDF', ['presupuesto/export-pdf'], [
                                    'target' => '_blank',
                                    'class' => 'btn btn-light buttons-excel buttons-html5',
                                    'data-pjax' => '0',
                                ]) ?>
                                <?= Html::a('Print', ['presupuesto/print'], [
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
                            <?= Html::a('<i class="bx bx-list-ol mr-1"></i>Listado Presupuestos', ['index'], ['class' => 'btn text-orange radius-30']) ?>
                        </div>
                    </div>



                        <div class="table-responsive">
                        <?= GridView::widget([
                        'id' => 'presupuestos-grid-view',
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
                                'attribute' => 'pre_numero',
                                'label' => '# Factura',
                            ],
                            [
                                'attribute' => 'cli_id',
                                'label' => 'Nombre / Razón Social',
                                'value' => 'cli.cli_nombre',
                            ],
                            [
                                'attribute' => 'pre_total',
                                'label' => 'Importe',
                                'format' => 'currency',
                            ],
                            [
                                'attribute' => 'soc_numero',
                                'label' => 'Cód. Socio',
                                'value' => 'soc.soc_numero',
                            ],
                            [
                                'attribute' => 'pre_fecha',
                                'label' => 'Fecha',
                                'format' => ['date', 'php:d-m-Y'],
                            ],
                            [
                                'attribute' => 'pre_aprobado',
                                'label' => 'Aprobado',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->pre_aprobado 
                                        ? '<span class="badge bg-success">Aprobado</span>' 
                                        : '<span class="badge bg-warning text-dark">No Aprobado</span>';
                                },
                            ],
                            // [
                            //     'attribute' => 'pre_situacion',
                            //     'label' => 'Situación',
                            //     'format' => 'raw',
                            //     'value' => function ($model) {
                            //         $date = $model->pre_fecha_situacion ? Yii::$app->formatter->asDate($model->pre_fecha_situacion, 'php:d-m-Y') : '';
                            //         return $model->pre_situacion . '<br>' . $date;
                            //     },
                            // ],

                            [
                                'class' => ActionColumn::class,
                                'header' => 'Acciones',
                                'template' => '<div class="d-flex flex-column align-items-center gap-2">{group1}{group2}</div>',
                                'buttons' => [
                                    'group1' => function ($url, $model, $key) {
                                        $buttons = [];
                                        $buttons[] = Html::a('<i class="bx bx-id-card"></i>', Url::toRoute(['view', 'pre_id' => $model->pre_id, 'view' => 'modal']), [
                                            'title' => 'Ver Presupuesto: ' . $model->pre_numero,
                                            'class' => 'btn btn-light',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#action-modal'
                                        ]);
                                        $buttons[] = Html::a('<i class="bx bx-edit"></i>', Url::toRoute(['update', 'pre_id' => $model->pre_id, 'view' => 'modal']), [
                                            'title' => 'Editar Presupuesto: ' . $model->pre_numero,
                                            'class' => 'btn btn-light',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#action-modal'
                                        ]);
                                        $buttons[] = Html::a('<i class="bx bx-printer"></i>', Url::toRoute(['print', 'pre_id' => $model->pre_id]), [
                                            'title' => 'Imprimir Presupuesto',
                                            'class' => 'btn btn-light',
                                            'target' => '_blank',
                                            'data-pjax' => '0',
                                        ]);
                                        return Html::tag('div', implode('', $buttons), ['class' => 'd-inline-flex gap-1']);
                                    },
                                    'group2' => function ($url, $model, $key) {
                                        $buttons = [];
                                        $buttons[] = Html::a('<i class="bx bx-envelope"></i>', Url::toRoute(['send-email', 'pre_id' => $model->pre_id]), [
                                            'title' => 'Enviar por Correo',
                                            'class' => 'btn btn-light',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#action-modal',
                                        ]);
                                        $buttons[] = Html::a('<i class="bx bx-toggle-left"></i>', Url::toRoute(['change-status', 'pre_id' => $model->pre_id]), [
                                            'title' => 'Cambiar Situación',
                                            'class' => 'btn btn-light',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#action-modal',
                                        ]);
                                        $buttons[] = Html::button('<i class="bx bx-check-shield"></i>', [
                                            'class' => 'btn btn-light toggle-approval-btn',
                                            'title' => $model->pre_aprobado ? 'Desaprobar' : 'Aprobar',
                                            'data-url' => Url::to(['toggle-aprobacion', 'pre_id' => $model->pre_id]),
                                            'onclick' => 'toggleApproval(this)',
                                        ]);
                                        $buttons[] = Html::a('<i class="bx bx-trash"></i>', Url::toRoute(['delete', 'pre_id' => $model->pre_id]), [
                                            'title' => 'Eliminar Presupuesto',
                                            'class' => 'btn btn-light',
                                            'data-confirm' => '¿Está seguro de que desea eliminar el presupuesto: "' . $model->pre_numero . '"?',
                                            'data-method' => 'post',
                                            'data-pjax' => '1',
                                        ]);
                                        return Html::tag('div', implode('', $buttons), ['class' => 'd-inline-flex gap-1']);
                                    },
                                ],
                                'urlCreator' => fn($action, Presupuesto $model, $key, $index, $column) =>
                                Url::toRoute([$action, 'pre_id' => $model->pre_id, 'view' => ($action === 'delete' ? null : 'modal')])
                            ],
                        ],
                    ]); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php Pjax::end(); ?>
    </div>
</div>