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
$this->registerJs(<<<JS
let timeout;
$('#concepto-facturacion-search-input').on('input', function() {
    clearTimeout(timeout);
    timeout = setTimeout(function() {
        $.pjax.submit($('#auto-search-form'), '#concepto-facturacion-pjax');
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
                $.pjax.reload({container: '#concepto-facturacion-pjax', async: false}); // async: false para esperar a que pjax termine
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
<?= $this->render('@app/views/layouts/_orangemenu') ?>
<div class="page-content">
<?php Pjax::begin(['id' => 'concepto-facturacion-pjax']); ?>
    <div class="col d-flex justify-content-between align-items-start">
        <h6 class="mb-0 text-uppercase">Conceptos de Facturación <dl><?= $dataProvider->getTotalCount() ?></dl>
        </h6>
        <div>
            <?= Html::a('<i class="bx bx-plus mr-1"></i> Crear Concepto Facturación', ['create', 'view' => 'modal'], [
                'class' => 'btn btn-success radius-30',
                'title' => 'Crear Concepto Facturación',
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#action-modal',
            ]) ?>
        </div>
    </div>
    <hr />
    <div class="row">
        
        <div class="col-xl-12 mx-auto">
            <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dt-buttons btn-group">
                            <?= Html::a('Excel', ['concepto-facturacion/export-excel'], [
                                'target' => '_blank',
                                'class' => 'btn btn-light buttons-excel buttons-html5',
                                'data-pjax' => 0,
                            ]) ?>
                            <?= Html::a('PDF', ['concepto-facturacion/export-pdf'], [
                                'target' => '_blank',
                                'class' => 'btn btn-light buttons-excel buttons-html5',
                                'data-pjax' => 0,
                            ]) ?>
                            <?= Html::a('Print', ['concepto-facturacion/print'], [
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

                            <?= $form->field($searchModel, 'cof_nombre', ['template' => '{input}'])
                                ->textInput([
                                    'placeholder' => 'Buscar por nombre de concepto...',
                                    'class' => 'form-control form-control-sm',
                                    'id' => 'concepto-facturacion-search-input',
                                    'autocomplete' => 'off'
                                ]) ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>


                    <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'summary' => false,
                        'tableOptions' => ['class' => 'table table-striped table-bordered'],
                        'columns' => [
                            [
                                'attribute' => 'cof_codigo',
                                'label' => 'Código',
                            ],
                            [
                                'attribute' => 'cof_nombre',
                                'label' => 'Nombre',
                            ],
                            [
                                'class' => ActionColumn::class,
                                'header' => 'Acciones',
                                'template' => '{update}',
                                'buttons' => [
                                    'update' => fn($url, $model) => Html::a('<i class="bx bx-edit"></i>', $url, [
                                        'title' => 'Editar Concepto facturacion: ' . $model->cof_nombre,
                                        'class' => 'btn btn-light',
                                        'data-bs-toggle' => 'modal',
                                        'data-bs-target' => '#action-modal'
                                    ]),
                                ],
                                'urlCreator' => function ($action, $model, $key, $index) {
                                    if ($action === 'update') {
                                        return Url::to(['update', 'id' => $model->cof_id, 'view' => 'modal']);
                                    }
                                    return null;
                                }   
                             ],
                        ],
                    ]); ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <?php Pjax::end(); 
?>
</div>