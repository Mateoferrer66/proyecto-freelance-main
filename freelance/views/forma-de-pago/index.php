<?php

use app\models\FormaDePago;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Modal;


/** @var yii\web\View $this */
/** @var app\models\FormaDePagoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestión de Formas de Pago';
$this->params['breadcrumbs'] = [];
?>

<?php
// Añadimos el modal de Bootstrap 5
Modal::begin([
    'id' => 'action-modal',
    'title' => '<h4 class="modal-title"></h4>',
    'size' => 'modal-lg',
    'footer' => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>',
]);

echo "<div id='modal-content'></div>";

Modal::end();

$this->registerJs(<<<'JS'
// Abrir modal para ver/editar
let actionModalInstance = new bootstrap.Modal(document.getElementById('action-modal'));

$(document).on('click', 'a[data-bs-toggle="modal"]', function(e) {
    e.preventDefault();
    // Reutilizamos la instancia del modal en lugar de crear una nueva
    const modalTitle = $('#action-modal .modal-title');
    const modalContent = $('#modal-content');
    const url = $(this).attr('href');

    modalTitle.text($(this).attr('title'));
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

// Limpiar el contenido del modal cuando se cierra para evitar que se muestren datos antiguos
// y para destruir el validador de formularios de Yii, previniendo conflictos.
document.getElementById('action-modal').addEventListener('hidden.bs.modal', function () {
    const modalContent = $('#modal-content');
    if (modalContent.find('form').length > 0) {
        modalContent.find('form').yiiActiveForm('destroy');
    }
    modalContent.html('');
});

// Enviar formulario de edición/creación vía AJAX
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
                // Recargar Pjax para actualizar el GridView
                $.pjax.reload({container: '#forma-de-pago-pjax'}); 
                // Opcional: mostrar una notificación de éxito (ej. con Toast)
            } else {
                // Mostrar errores de validación
                form.yiiActiveForm('updateMessages', response.errors, true);
            }
        },
        error: function() {
            alert('Ocurrió un error al guardar. Por favor, inténtelo de nuevo.');
        },
        complete: function() {
            submitButton.prop('disabled', false).html('Guardar');
        }
    });
    return false; // Prevenir el envío normal del formulario
});
JS);
?>
<div class="forma-de-pago-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e2f;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #2a2a3b;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            text-transform: uppercase;
            color: #ffa500;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-bar input {
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            width: 300px;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #ffa500;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            text-transform: uppercase;
        }

        .buttons button:hover {
            background-color: #ff8c00;
        }
    </style>

    <div class="mb-3">
        <?= Html::a('<i class="bx bx-plus-medical"></i> Crear Forma de Pago', ['create', 'view' => 'modal'], [
            'class' => 'btn btn-success px-4 radius-30',
            'title' => 'Agregar nueva forma de pago',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#action-modal'
        ]) ?>
    </div>

    <div class="col d-flex justify-content-between align-items-start">
        <h6 class="mb-0 text-uppercase">
            Forma de pago <span class="badge bg-warning text-dark"><?= $dataProvider->getTotalCount() ?></span>
        </h6>
    </div>

    <?php Pjax::begin(['id' => 'forma-de-pago-pjax']); ?>

    <div class="container">
        <div class="header">
            <h1>Gestión de Formas de Pago</h1>
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'search-bar'],
            ]); ?>

            <?= $form->field($searchModel, 'fdp_nombre', ['template' => '{input}'])
                ->textInput(['placeholder' => 'Buscar forma de pago...']) ?>

            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="buttons">
            <?= Html::a('Excel', ['forma-de-pago/export-excel'], [
                'target' => '_blank',
                'style' => 'padding:10px 20px;border:none;border-radius:5px;background-color:#ffa500;color:#fff;cursor:pointer;font-size:16px;text-transform:uppercase;text-decoration:none;'
            ]) ?>
            <?= Html::a('PDF', ['forma-de-pago/export-pdf'], [
                'target' => '_blank',
                'style' => 'padding:10px 20px;border:none;border-radius:5px;background-color:#ffa500;color:#fff;cursor:pointer;font-size:16px;text-transform:uppercase;text-decoration:none;'
            ]) ?>
            <?= Html::a('Print', ['forma-de-pago/print'], [
                'target' => '_blank',
                'style' => 'padding:10px 20px;border:none;border-radius:5px;background-color:#ffa500;color:#fff;cursor:pointer;font-size:16px;text-transform:uppercase;text-decoration:none;'
            ]) ?>
        </div>


        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'columns' => [
                [
                    'attribute' => 'fdp_id',
                    'label' => 'Código',
                ],
                [
                    'attribute' => 'fdp_nombre',
                    'label' => 'Nombre',
                ],
                [
                    'class' => ActionColumn::className(),
                    'header' => 'Acciones',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a('<i class="bx bx-show"></i>', $url, [
                                'title' => 'Ver FormaDePago', 
                                'class' => 'btn btn-info btn-sm', 
                                'data-bs-toggle' => 'modal', 
                                'data-bs-target' => '#action-modal'
                            ]);
                        },
                        'update' => function ($url, $model, $key) {
                            return Html::a('<i class="bx bx-edit"></i>', $url, [
                                'title' => 'Editar FormaDePago', 
                                'class' => 'btn btn-primary btn-sm', 
                                'data-bs-toggle' => 'modal', 
                                'data-bs-target' => '#action-modal'
                            ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<i class="bx bx-trash"></i>', $url, [
                                'title' => 'Eliminar FormaDePago', 'class' => 'btn btn-danger btn-sm',
                                'data-confirm' => '¿Estás seguro de que quieres eliminar este elemento?',
                                'data-method' => 'post',
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, FormaDePago $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'fdp_id' => $model->fdp_id, 'view' => 'modal']);
                    }
                ],
            ],
        ]); ?>

    </div>

    <?php Pjax::end(); ?>

</div>