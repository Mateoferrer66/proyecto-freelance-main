<?php

use app\models\Usuario;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;
use yii\grid\CheckboxColumn;

/** @var yii\web\View $this */
/** @var app\models\UsuarioSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestión de Usuarios';
$this->params['breadcrumbs'] = [];
$this->registerCss(".table thead a { text-decoration: none !important; }");

// JS for search
$this->registerJs(<<<JS
let timeout;
$('#usuario-search-input').on('input', function() {
    clearTimeout(timeout);
    timeout = setTimeout(function() {
        $.pjax.submit($('#auto-search-form'), '#usuarios-pjax');
    }, 500);
});
JS);

// JS for batch delete
$this->registerJs(<<<JS
$('#batch-delete-button').on('click', function() {
    var keys = $('#usuarios-grid-view').yiiGridView('getSelectedRows');
    if (keys.length === 0) {
        alert('Debe seleccionar al menos un usuario para eliminar.');
        return;
    }

    if (confirm('¿Está seguro de que desea eliminar los ' + keys.length + ' usuarios seleccionados?')) {
        $.ajax({
            url: 'index.php?r=usuario/batch-delete',
            type: 'post',
            data: { ids: keys },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $.pjax.reload({container: '#usuarios-pjax'});
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
                $.pjax.reload({container: '#usuarios-pjax', async: false}); // async: false para esperar a que pjax termine
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
        <h6 class="mb-0 text-uppercase">Usuarios <dl><?= $dataProvider->getTotalCount() ?></dl>
        </h6>
        <div>
            <?= Html::a('<i class="bx bx-plus mr-1"></i> Crear Usuario', ['create'], [
                'class' => 'btn btn-success radius-30',
                'title' => 'Crear Usuario',
            ]) ?>
        </div>
    </div>
    <hr />



    <div class="card">
        <div class="card-body">
            <?php Pjax::begin(['id' => 'usuarios-pjax']); ?>
            <div class="col-xl-12 mx-auto">
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="d-flex justify-content-end align-items-center mb-3">
                            <?= Html::button('<i class="bx bx-trash"></i> ELIMINAR', [
                                'class' => 'btn text-orange radius-30',
                                'id' => 'batch-delete-button'
                            ]) ?>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="dt-buttons btn-group">
                                <?= Html::a('Excel', ['usuario/export-excel'], [
                                    'target' => '_blank',
                                    'class' => 'btn btn-light buttons-excel buttons-html5',
                                    'data-pjax' => '0',
                                ]) ?>
                                <?= Html::a('PDF', ['usuario/export-pdf'], [
                                    'target' => '_blank',
                                    'class' => 'btn btn-light buttons-excel buttons-html5',
                                    'data-pjax' => '0',
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

                                <?= $form->field($searchModel, 'usu_nombre', ['template' => '{input}'])
                                    ->textInput([
                                        'placeholder' => 'Buscar por nombre de usuario...',
                                        'class' => 'form-control form-control-sm',
                                        'id' => 'usuario-search-input',
                                        'autocomplete' => 'off'
                                    ]) ?>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>

                        <div class="table-responsive">
                        <?= GridView::widget([
                            'id' => 'usuarios-grid-view',
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
                                    'attribute' => 'usu_nombre',
                                    'label' => 'Nombre',
                                    'format' => 'raw',
                                    'contentOptions' => function ($model, $key, $index, $column) {
                                        $class = $model->usu_estado === Usuario::USU_ESTADO_ACTIVO ? 'greenGdt' : 'redGdt';
                                        return ['class' => $class];
                                    },
                                    'value' => function ($model) {
                                        return '<font class="text-white">' . $model->usu_nombre . '</font>';
                                    }
                                ],
                                [
                                    'attribute' => 'usu_apellido',
                                    'label' => 'Apellido',
                                ],
                                [
                                    'attribute' => 'usu_estado',
                                    'label' => 'Estado',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->usu_estado === Usuario::USU_ESTADO_ACTIVO) {
                                            return '<i class="bx bx-radio-circle-marked bx-burst align-middle font-18 me-1 text-success"></i>' . $model->usu_estado;
                                        } else {
                                            return '<i class="bx bx-radio-circle-marked align-middle font-18 me-1 text-danger"></i>' . $model->usu_estado;
                                        }
                                    },
                                ],
                                [
                                    'class' => ActionColumn::class,
                                    'header' => 'Acciones',
                                    'template' => '{view} {update} {delete}',
                                    // 'template' => '{view} {update} {delete} {toggle}',
                                    'buttons' => [
                                        'view' => fn($url, $model) => Html::a('<i class="bx bx-id-card"></i>', $url, [
                                            'title' => 'Ver Usuario: ' . $model->usu_nombre,
                                            'class' => 'btn btn-light',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#action-modal'
                                        ]),
                                        'update' => fn($url, $model) => Html::a('<i class="bx bx-edit"></i>', $url, [
                                            'title' => 'Editar Usuario: ' . $model->usu_nombre,
                                            'class' => 'btn btn-light',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#action-modal'
                                        ]),
                                        'delete' => fn($url, $model) => Html::a('<i class="bx bx-trash"></i>', $url, [
                                            'title' => 'Eliminar Usuario',
                                            'class' => 'btn btn-light',
                                            'data-confirm' => '¿Está seguro de que desea eliminar el usuario: "' . $model->usu_nombre . '"?',
                                            'data-method' => 'post',
                                            'data-pjax' => '1',
                                        ]),
                                        // 'toggle' => function ($url, $model, $key) {
                                        //     if ($model->usu_estado === Usuario::USU_ESTADO_ACTIVO) {
                                        //         return Html::a('<i class="bx bx-power-off"></i>', ['toggle-status', 'usu_id' => $model->usu_id], [
                                        //             'title' => 'Desactivar Usuario',
                                        //             'class' => 'btn btn-light',
                                        //             'data-confirm' => '¿Está seguro de que desea desactivar a ' . $model->usu_nombre . '?',
                                        //             'data-method' => 'post',
                                        //             'data-pjax' => '1',
                                        //         ]);
                                        //     } else {
                                        //         return Html::a('<i class="bx bx-check"></i>', ['toggle-status', 'usu_id' => $model->usu_id], [
                                        //             'title' => 'Activar Usuario',
                                        //             'class' => 'btn btn-light',
                                        //             'data-confirm' => '¿Está seguro de que desea activar a ' . $model->usu_nombre . '?',
                                        //             'data-method' => 'post',
                                        //             'data-pjax' => '1',
                                        //         ]);
                                        //     }
                                        // },
                                    ],
                                    'urlCreator' => fn($action, Usuario $model, $key, $index, $column) =>
                                    Url::toRoute([$action, 'usu_id' => $model->usu_id, 'view' => ($action === 'delete' || $action === 'toggle' ? null : 'modal')]),
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