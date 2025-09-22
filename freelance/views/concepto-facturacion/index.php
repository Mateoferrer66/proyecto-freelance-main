<?php

use app\models\ConceptoFacturacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestión de Conceptos de Facturación';
$this->params['breadcrumbs'] = [];
$this->registerCss(".table thead a { text-decoration: none !important; }");

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
        margin-bottom: 20px;
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

<?php
Modal::begin([
    'id' => 'action-modal',
    'title' => '<h4 class="modal-title"></h4>',
    'size' => 'modal-lg',
    'footer' => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>',
]);
echo "<div id='modal-content'><div class='text-center'><div class='spinner-border' role='status'></div></div></div>";
Modal::end();

$this->registerJs(<<<'JS'
let actionModalInstance = new bootstrap.Modal(document.getElementById('action-modal'));

$(document).on('click', '[data-bs-toggle="modal"]', function(e) {
    e.preventDefault();
    const modalTitle = $('#action-modal .modal-title');
    const modalContent = $('#modal-content');
    const url = $(this).attr('href');
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

document.getElementById('action-modal').addEventListener('hidden.bs.modal', function () {
    const modalContent = $('#modal-content');
    if (modalContent.find('form').length > 0) {
        modalContent.find('form').yiiActiveForm('destroy');
    }
    modalContent.html('');
    $('#action-modal .modal-title').text('');
});

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
                $.pjax.reload({container: '#concepto-pjax', async: false});
            } else {
                form.yiiActiveForm('updateMessages', response.errors, true);
            }
        },
        error: function() {
            alert('Ocurrió un error al procesar la solicitud.');
        },
        complete: function() {
            submitButton.prop('disabled', false).html(form.find('button[type="submit"]').text().includes('Crear') ? 'Crear' : 'Actualizar');
        }
    });

    return false;
});
JS);
?>

<div class="concepto-facturacion-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <div class="mb-3" style="margin-top: 20px;">
        <?= Html::a('<i class="bx bx-plus-medical"></i> Crear Concepto', ['create', 'view' => 'modal'], [
            'class' => 'btn btn-success px-4 radius-30',
            'title' => 'Agregar Nuevo Concepto de Facturación',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#action-modal',
        ]) ?>
    </div>

    <?php Pjax::begin(['id' => 'concepto-pjax']); ?>

    <div class="col d-flex justify-content-between align-items-start">
        <h6 class="mb-0 text-uppercase">
            Conceptos de Facturación <span class="badge bg-warning text-dark"><?= $dataProvider->getTotalCount() ?></span>
        </h6>
    </div>

    <div class="container">
        <div class="header">
            <h1>Gestión de Conceptos</h1>
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'search-bar', 'data-pjax' => 1],
            ]); ?>

            <?= $form->field($searchModel, 'cof_nombre', ['template' => '{input}'])
                ->textInput(['placeholder' => 'Buscar por nombre...']) ?>

            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="buttons">
            <?= Html::a('Excel', ['concepto-facturacion/export-excel'], ['target' => '_blank', 'data-pjax' => 0]) ?>
            <?= Html::a('PDF', ['concepto-facturacion/export-pdf'], ['target' => '_blank', 'data-pjax' => 0]) ?>
            <?= Html::a('Print', ['concepto-facturacion/print'], ['target' => '_blank', 'data-pjax' => 0]) ?>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'tableOptions' => ['class' => 'table table-striped table-bordered'],
            'columns' => [
                'cof_codigo',
                'cof_nombre',
                [
                    'attribute' => 'iva_id',
                    'value' => fn($model) => $model->iva ? $model->iva->iva_concepto : 'N/A',
                ],
                'cof_clasificacion',
                [
                    'class' => ActionColumn::class,
                    'header' => 'Acciones',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => fn($url, $model) => Html::a('<i class="bx bx-show"></i>', $url, [
                            'title' => 'Ver Concepto: ' . $model->cof_nombre,
                            'class' => 'btn btn-info btn-sm',
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#action-modal'
                        ]),
                        'update' => fn($url, $model) => Html::a('<i class="bx bx-edit"></i>', $url, [
                            'title' => 'Editar Concepto: ' . $model->cof_nombre,
                            'class' => 'btn btn-primary btn-sm',
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#action-modal'
                        ]),
                        'delete' => fn($url, $model) => Html::a('<i class="bx bx-trash"></i>', $url, [
                            'title' => 'Eliminar Concepto',
                            'class' => 'btn btn-danger btn-sm',
                            'data-confirm' => '¿Está seguro de que desea eliminar el concepto: "' . $model->cof_nombre . '"?',
                            'data-method' => 'post',
                            'data-pjax' => '1',
                        ]),
                    ],
                    'urlCreator' => fn($action, ConceptoFacturacion $model, $key, $index, $column) => 
                        Url::toRoute([$action, 'id' => $model->cof_id, 'view' => ($action === 'delete' ? null : 'modal')]),
                ],
            ],
        ]); ?>
    </div>

    <?php Pjax::end(); ?>
</div>
