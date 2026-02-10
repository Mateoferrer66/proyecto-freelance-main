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
let timeout;
$('#cliente-search-input').on('input', function() {
    clearTimeout(timeout);
    timeout = setTimeout(function() {
        $.pjax.submit($('#auto-search-form'), '#socios-pjax');
    }, 500);
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

    if (confirm('¿Está seguro de que desea eliminar los ' + keys.length + ' socios seleccionados?')) {
        $.ajax({
            url: '$batchDeleteUrl',
            type: 'post',
            data: { ids: keys },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $.pjax.reload({container: '#socios-pjax'});
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
                $.pjax.reload({container: '#socios-pjax', async: false}); // async: false para esperar a que pjax termine
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
            <form name="noticias" method="get" class="mb-4">
                <div class="row">
                    <div class="col-md-2">
                        <input class="result form-control" type="text" id="date-time" placeholder="Fecha inicial">
                    </div>
                    <div class="col-md-2">
                        <input class="result form-control" type="text" id="date-time2" placeholder="Fecha final">
                    </div>
                    <div class="col-md-1">
                        <a href="#" class="btn btn-success radius-30"><i class="bx bx-search-alt mr-1"></i></a>
                    </div>
                    <div class="col-md-7 d-flex justify-content-end">
                        <?= Html::button('<i class="bx bx-trash"></i> Eliminar', [
                                'class' => 'btn text-orange radius-30',
                                'id' => 'batch-delete-button'
                        ]) ?>
                        <a href="#" class="btn text-orange radius-30"><i class="bx bx-list-ol mr-1"></i>Altas/Bajas</a>
                        <a href="#" class="btn text-orange radius-30"><i class="bx bx-list-ol mr-1"></i>Listado Socios</a>
                    </div>
                </div>
            </form>
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
                                return $model->category->cat_nombre ?? '-';
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
                            'template' => '<div class="action-grid-2x2">{toggle} {view} {update} {delete}</div>',
                            'headerOptions' => ['class' => 'text-start', 'style' => 'width: 1%; white-space: nowrap;'], // Shrink-wrap & Left align
                            'contentOptions' => ['class' => 'text-end', 'style' => 'width: 1%; white-space: nowrap;'], // Shrink-wrap & Right align content
                            'buttons' => [
                                'toggle' => function ($url, $model, $key) {
                                    if ($model->soc_estado === Socio::SOC_ESTADO_ACTIVO) {
                                        return Html::a('<i class="bx bx-block"></i>', ['toggle-status', 'soc_id' => $model->soc_id], [
                                                    'title' => 'Desactivar Socio',
                                                    'class' => 'btn btn-light',
                                                    'data-confirm' => '¿Está seguro de que desea desactivar a ' . $model->soc_nombre . '?',
                                                    'data-method' => 'post',
                                                    'data-pjax' => '0',
                                        ]);
                                    } else {
                                        return Html::a('<i class="bx bx-check"></i>', ['toggle-status', 'soc_id' => $model->soc_id], [
                                                    'title' => 'Activar Socio',
                                                    'class' => 'btn btn-light',
                                                    'data-confirm' => '¿Está seguro de que desea activar a ' . $model->soc_nombre . '?',
                                                    'data-method' => 'post',
                                                    'data-pjax' => '0',
                                        ]);
                                    }
                                },
                                'view' => fn($url, $model) => Html::a('<i class="bx bx-id-card"></i>', $url, [
                                    'title' => 'Ver Socio: ' . $model->soc_nombre,
                                    'class' => 'btn btn-light',
                                    'data-bs-toggle' => 'modal',
                                    'data-bs-target' => '#action-modal'
                                ]),
                                'update' => fn($url, $model) => Html::a('<i class="bx bx-edit"></i>', $url, [
                                    'title' => 'Editar Socio: ' . $model->soc_nombre,
                                    'class' => 'btn btn-light',
                                    'data-bs-toggle' => 'modal',
                                    'data-bs-target' => '#action-modal'
                                ]),
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