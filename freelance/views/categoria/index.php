<?php

use app\models\Categoria;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CategoriaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestión de Categorías';
$this->params['breadcrumbs'] = []; // Remove breadcrumbs

// --- Modal Implementation ---
Modal::begin([
    'id' => 'action-modal',
    'title' => '<h4 class="modal-title"></h4>',
    'size' => 'modal-lg',
    'footer' => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>',
]);
echo "<div id='modal-content'><div class='text-center'><div class='spinner-border' role='status'></div></div></div>";
Modal::end();

// --- JavaScript for Modal and Pjax ---
$this->registerJs(<<<'JS'
let actionModalInstance = new bootstrap.Modal(document.getElementById('action-modal'));

// Handler to open modal and load content
$(document).on('click', '[data-bs-toggle="modal"]', function(e) {
    e.preventDefault();
    const modal = $('#action-modal');
    const modalTitle = modal.find('.modal-title');
    const modalContent = modal.find('#modal-content');
    const url = $(this).attr('href');
    const title = $(this).attr('title') || $(this).data('title');

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

// Handler for form submission inside modal
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
                $.pjax.reload({container: '#categoria-pjax', async: false});
            } else {
                // In case of validation errors, Yii2 AJAX validation will handle displaying them.
                // If the response is not a JSON with errors, we might need to replace the content.
                if (typeof response.errors === 'undefined') {
                    $('#modal-content').html(response);
                }
            }
        },
        error: function() {
            alert('Ocurrió un error al procesar la solicitud.');
        },
        complete: function() {
             submitButton.prop('disabled', false).html('Guardar');
        }
    });

    return false; // Prevent traditional submission
});

// Clean up modal on close
document.getElementById('action-modal').addEventListener('hidden.bs.modal', function () {
    $('#modal-content').html('');
    $('#action-modal .modal-title').text('');
});
JS);
?>

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

    .buttons a {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #ffa500;
        color: #fff;
        cursor: pointer;
        font-size: 16px;
        text-transform: uppercase;
        text-decoration: none;
    }

    .buttons a:hover {
        background-color: #ff8c00;
    }
</style>

<div class="categoria-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <div class="mb-3" style="margin-top:20px;">
        <?= Html::a('<i class="bx bx-plus-medical"></i> Crear Categoría', ['create', 'view' => 'modal'], [
            'class' => 'btn btn-success px-4 radius-30',
            'title' => 'Crear Nueva Categoría',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#action-modal'
        ]) ?>
    </div>

    <div class="col d-flex justify-content-between align-items-start">
        <h6 class="mb-0 text-uppercase">
            Categorías <span class="badge bg-warning text-dark"><?= $dataProvider->getTotalCount() ?></span>
        </h6>
    </div>

    <div class="container">
        <div class="header">
            <h1>Gestión de Categorías</h1>
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'search-bar', 'data-pjax' => 1],
            ]); ?>

            <?= $form->field($searchModel, 'cat_codigo', ['template' => '{input}'])
                ->textInput(['placeholder' => 'Buscar código...']) ?>
            <?= $form->field($searchModel, 'cat_nombre', ['template' => '{input}'])
                ->textInput(['placeholder' => 'Buscar nombre...']) ?>

            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="buttons">
            <?= Html::a('Excel', ['export-excel'], ['class' => 'btn btn-outline-light', 'data-pjax' => 0, 'target' => '_blank']) ?>
            <?= Html::a('PDF', ['export-pdf'], ['class' => 'btn btn-outline-light', 'data-pjax' => 0, 'target' => '_blank']) ?>
            <?= Html::a('Print', ['print'], ['class' => 'btn btn-outline-light', 'data-pjax' => 0, 'target' => '_blank']) ?>
        </div>

        <?php Pjax::begin(['id' => 'categoria-pjax']); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => "Mostrando {begin}-{end} de {totalCount} elementos",
            'tableOptions' => ['class' => 'table table-dark table-striped table-hover'],
            'headerRowOptions' => ['class' => 'text-warning'],
            'columns' => [
                [
                    'attribute' => 'cat_id',
                    'label' => 'Código',
                ],
                [
                    'attribute' => 'cat_nombre',
                    'label' => 'Categoria',
                ],
                [
                    'class' => ActionColumn::class,
                    'header' => 'Acciones',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => fn($url, $model) => Html::a('<i class="bx bx-show"></i>', $url, [
                            'title' => 'Ver Categoría: ' . $model->cat_nombre,
                            'class' => 'btn btn-info btn-sm',
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#action-modal'
                        ]),
                        'update' => fn($url, $model) => Html::a('<i class="bx bx-edit"></i>', $url, [
                            'title' => 'Editar Categoría: ' . $model->cat_nombre,
                            'class' => 'btn btn-warning btn-sm',
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#action-modal'
                        ]),
                        'delete' => fn($url, $model) => Html::a('<i class="bx bx-trash"></i>', $url, [
                            'title' => "Eliminar '{$model->cat_nombre}'",
                            'class' => 'btn btn-danger btn-sm',
                            'data-confirm' => "¿Eliminar la categoría '{$model->cat_nombre}'?",
                            'data-method' => 'post',
                            'data-pjax' => '1'
                        ]),
                    ],
                    'urlCreator' => fn($action, Categoria $model, $key, $index, $column) =>
                        Url::toRoute([$action, 'cat_id' => $model->cat_id, 'view' => ($action === 'delete' ? null : 'modal')]),
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>
    </div>

</div>
