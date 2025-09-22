<?php

use app\models\TipoDocIdentidad;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;


/** @var yii\web\View $this */
/** @var app\models\TipoDocIdentidadSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestión de Tipos de Documento de Identidad';
$this->params['breadcrumbs'] = [];

// Definición del Modal
Modal::begin([
    'id' => 'action-modal',
    'title' => '<h4 class="modal-title"></h4>',
    'size' => 'modal-lg',
    'options' => ['class' => 'modal fade'],
    'footer' => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>',
]);
echo "<div id='modal-content'><div class=\"text-center\">Cargando...</div></div>";
Modal::end();

$this->registerJs(<<<JS
let actionModalInstance;

// Asegurarse de que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    const actionModalElement = document.getElementById('action-modal');
    if (actionModalElement) {
        actionModalInstance = new bootstrap.Modal(actionModalElement);
    }
});

// Evento para abrir el modal y cargar contenido
$(document).on('click', '[data-bs-toggle="modal"]', function(e) {
    e.preventDefault();
    const url = $(this).attr('href');
    const modal = $('#action-modal');
    const modalTitle = modal.find('.modal-title');
    const modalContent = modal.find('#modal-content');

    // Actualizar título y mostrar 'Cargando...'
    modalTitle.text($(this).attr('title'));
    modalContent.html('<p class="text-center">Cargando...</p>');
    
    if(actionModalInstance){
        actionModalInstance.show();
    }

    // Cargar contenido via AJAX
    $.get(url, function(data) {
        modalContent.html(data);
    }).fail(function() {
        modalContent.html('<p class="text-center text-danger">Error al cargar el contenido.</p>');
    });
});

// Evento para enviar el formulario dentro del modal via AJAX
$(document).on('submit', '#tipo-doc-form', function(e) {
    e.preventDefault();
    const form = $(this);
    const submitButton = form.find('button[type="submit"]');
    submitButton.prop('disabled', true).html('Guardando...');

    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function(response) {
            if (response.success) {
                if(actionModalInstance){
                    actionModalInstance.hide();
                }
                // Recargar Pjax
                $.pjax.reload({container: '#tipo-doc-pjax', async: false});
            } else {
                // Si hay errores de validación, el controlador debería devolver el formulario renderizado con errores.
                // Se reemplaza el contenido del modal para mostrar los errores.
                $('#modal-content').html(response);
            }
        },
        error: function() {
            alert('Error al procesar la solicitud.');
        },
        complete: function() {
            submitButton.prop('disabled', false).html('Guardar');
        }
    });
});

JS);
?>

<div class="tipo-doc-identidad-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <div class="tipo-doc-container" style="max-width:1200px;margin:50px auto;padding:20px;background-color:#2a2a3b;border-radius:10px;color:#fff;">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 style="color:#ffa500;">Tipos de Documento de Identidad</h1>
            <?= Html::a('<i class="bx bx-plus-medical"></i> Crear Tipo', ['create', 'view' => 'modal'], [
                'class' => 'btn btn-success',
                'title' => 'Crear nuevo Tipo de Documento',
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#action-modal'
            ]) ?>
        </div>

        <div class="header">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'search-bar', 'data-pjax' => 1],
            ]); ?>
            <div class="input-group">
                <?= $form->field($searchModel, 'tdo_nombre', ['template' => '{input}'])
                    ->textInput(['placeholder' => 'Buscar por nombre...', 'class' => 'form-control']) ?>
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

        <div class="buttons my-3">
            <?= Html::a('Excel', ['export-excel'], [
                'target' => '_blank',
                'class' => 'btn btn-outline-light',
                'style' => 'text-decoration:none;'
            ]) ?>
            <?= Html::a('PDF', ['export-pdf'], [
                'target' => '_blank',
                'class' => 'btn btn-outline-light',
                'style' => 'text-decoration:none;'
            ]) ?>
            <?= Html::a('Print', ['print'], [
                'target' => '_blank',
                'class' => 'btn btn-outline-light',
                'style' => 'text-decoration:none;'
            ]) ?>
        </div>

        <?php Pjax::begin(['id' => 'tipo-doc-pjax']); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'tableOptions' => ['class' => 'table table-dark table-striped table-hover'],
            'columns' => [
                'tdo_codigo',
                'tdo_nombre',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Acciones',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function($url, $model, $key) {
                            return Html::a('<i class="bx bx-show"></i>', $url, [
                                'title' => 'Ver ' . $model->tdo_nombre,
                                'class' => 'btn btn-info btn-sm',
                                'data-bs-toggle' => 'modal',
                                'data-bs-target' => '#action-modal'
                            ]);
                        },
                        'update' => function($url, $model, $key) {
                            return Html::a('<i class="bx bx-edit"></i>', $url, [
                                'title' => 'Editar ' . $model->tdo_nombre,
                                'class' => 'btn btn-warning btn-sm',
                                'data-bs-toggle' => 'modal',
                                'data-bs-target' => '#action-modal'
                            ]);
                        },
                        'delete' => function($url, $model, $key) {
                            return Html::a('<i class="bx bx-trash"></i>', $url, [
                                'title' => "Eliminar '" . $model->tdo_nombre . "'",
                                'class' => 'btn btn-danger btn-sm',
                                'data-confirm' => "Cuidado vas a eliminar a '" . $model->tdo_nombre . "' de forma permanente, ¿Estás seguro?",
                                'data-method' => 'post',
                                'data-pjax' => '1'
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, TipoDocIdentidad $model, $key, $index, $column) {
                        // Añadir 'view' => 'modal' para las acciones que lo requieran
                        if ($action === 'view' || $action === 'update') {
                            return Url::toRoute([$action, 'id' => $model->tdo_id, 'view' => 'modal']);
                        }
                        return Url::toRoute([$action, 'id' => $model->tdo_id]);
                    }
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>
    </div>
</div>
