<?php

use app\models\Cliente;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;
use yii\grid\CheckboxColumn;

/** @var yii\web\View $this */
/** @var app\models\ClienteSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestión de Clientes';
$this->params['breadcrumbs'] = [];
$this->registerCss(".table thead a { text-decoration: none !important; }");
$this->registerJs(<<<JS
let timeout;
$('#cliente-search-input').on('input', function() {
    clearTimeout(timeout);
    timeout = setTimeout(function() {
        $.pjax.submit($('#auto-search-form'), '#clientes-pjax');
    }, 500);
});
JS);


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
                $.pjax.reload({container: '#clientes-pjax', async: false}); // async: false para esperar a que pjax termine
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
JS);
?>
<div class="page-content" style="margin-top: 3.4rem;">

    <div class="col d-flex justify-content-between align-items-start">
        <h6 class="mb-0 text-uppercase">Clientes <dl><?= $dataProvider->getTotalCount() ?></dl>
        </h6>
        <div>
            <?= Html::a('<i class="bx bx-plus mr-1"></i> Crear Cliente', ['create', 'view' => 'modal'], [
                'class' => 'btn btn-success radius-30',
                'title' => 'Crear Cliente',
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#action-modal',
            ]) ?>
        </div>
    </div>
    <hr />
    <div class="card">
        <div class="card-body">
            <?php Pjax::begin(['id' => 'clientes-pjax']); ?>
            <div class="col-xl-12 mx-auto">
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dt-buttons btn-group">
                                <?= Html::a('Excel', ['cliente/export-excel'], [
                                    'target' => '_blank',
                                    'class' => 'btn btn-light buttons-excel buttons-html5',
                                    'data-pjax' => 0,
                                ]) ?>
                                <?= Html::a('PDF', ['cliente/export-pdf'], [
                                    'target' => '_blank',
                                    'class' => 'btn btn-light buttons-excel buttons-html5',
                                    'data-pjax' => 0,
                                ]) ?>
                                <?= Html::a('Print', ['cliente/print'], [
                                    'target' => '_blank',
                                    'class' => 'btn btn-light buttons-excel buttons-html5',
                                    'data-pjax' => 0,
                                ]) ?>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_filter">
                                <?php $form = ActiveForm::begin([
                                    'action' => ['index'],
                                    'method' => 'get',
                                    'options' => [
                                        'class' => 'search-bar mb-3',
                                        'data-pjax' => 1,
                                        'id' => 'auto-search-form'
                                    ],
                                ]); ?>

                                <?= $form->field($searchModel, 'cli_nombre', ['template' => '{input}'])
                                    ->textInput([
                                        'placeholder' => 'Buscar por nombre de cliente...',
                                        'class' => 'form-control form-control-sm',
                                        'id' => 'cliente-search-input',
                                        'autocomplete' => 'off'
                                    ]) ?>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>



                        <?= GridView::widget([
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
                                    'attribute' => 'cli_numdocide',
                                    'label' => 'NIF',
                                ],
                                [
                                    'attribute' => 'cli_nombre',
                                    'label' => 'Nombre/Razón Social',
                                    'format' => 'raw',
                                    'contentOptions' => function ($model, $key, $index, $column) {
                                        $class = $model->cli_estado === \app\models\Cliente::CLI_ESTADO_ACTIVO ? 'greenGdt' : 'redGdt';
                                        return ['class' => $class];
                                    },
                                    'value' => function($model) {
                                        return '<font class="text-white">' . $model->cli_nombre . '</font>';
                                    }
                                ],
                                [
                                    'attribute' => 'cli_estado',
                                    'label' => 'Estado',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->cli_estado === \app\models\Cliente::CLI_ESTADO_ACTIVO) {
                                            return '<i class="bx bx-radio-circle-marked bx-burst align-middle font-18 me-1 text-success"></i>' . $model->cli_estado;
                                        } else {
                                            return '<i class="bx bx-radio-circle-marked align-middle font-18 me-1 text-danger"></i>' . $model->cli_estado;
                                        }
                                    },
                                ],
                                [
                                    'class' => ActionColumn::class,
                                    'header' => 'Acciones',
                                    'template' => '{view} {update} {delete} {toggle}',
                                    'buttons' => [
                                        'toggle' => function ($url, $model, $key) {
                                            if ($model->cli_estado === \app\models\Cliente::CLI_ESTADO_ACTIVO) {
                                                return Html::a('<i class="bx bx-block"></i>', ['toggle-status', 'cli_id' => $model->cli_id], [
                                                    'title' => 'Desactivar Cliente',
                                                    'class' => 'btn btn-light',
                                                    'data-confirm' => '¿Está seguro de que desea desactivar a ' . $model->cli_nombre . '?',
                                                    'data-method' => 'post',
                                                    'data-pjax' => '1',
                                                ]);
                                            } else {
                                                return Html::a('<i class="bx bx-power-off"></i>', ['toggle-status', 'cli_id' => $model->cli_id], [
                                                    'title' => 'Activar Cliente',
                                                    'class' => 'btn btn-light',
                                                    'data-confirm' => '¿Está seguro de que desea activar a ' . $model->cli_nombre . '?',
                                                    'data-method' => 'post',
                                                    'data-pjax' => '1',
                                                ]);
                                            }
                                        },
                                        'view' => fn($url, $model) => Html::a('<i class="bx bx-id-card"></i>', $url, [
                                            'title' => 'Ver Cliente: ' . $model->cli_nombre,
                                            'class' => 'btn btn-light',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#action-modal'
                                        ]),
                                        'update' => fn($url, $model) => Html::a('<i class="bx bx-edit"></i>', $url, [
                                            'title' => 'Editar Cliente: ' . $model->cli_nombre,
                                            'class' => 'btn btn-light',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#action-modal'
                                        ]),
                                        'delete' => fn($url, $model) => Html::a('<i class="bx bx-trash"></i>', $url, [
                                            'title' => 'Eliminar Cliente',
                                            'class' => 'btn btn-light',
                                            'data-confirm' => '¿Está seguro de que desea eliminar el cliente: "' . $model->cli_nombre . '"?',
                                            'data-method' => 'post',
                                            'data-pjax' => '1',
                                        ]),
                                        
                                    ],
                                    'urlCreator' => fn($action, Cliente $model, $key, $index, $column) =>
                                    Url::toRoute([$action, 'cli_id' => $model->cli_id, 'view' => ($action === 'delete' ? null : 'modal')]),
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