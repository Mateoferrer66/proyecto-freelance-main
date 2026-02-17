<?php

use app\models\Portafolio;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PortafolioSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Portafolio';
$this->params['breadcrumbs'] = [];
$this->registerCss(".table thead a { text-decoration: none !important; }");

?>

<div class="page-content">
    
    <div class="col d-flex justify-content-between align-items-start">
        <h6 class="mb-0 text-uppercase">Proyectos de Portafolio <dl><?= $dataProvider->getTotalCount() ?></dl>
        </h6>
        <div>
            <?= Html::a('<i class="bx bx-plus mr-1"></i> Crear Proyecto', ['create'], [
                'class' => 'btn btn-success radius-30',
                'title' => 'Crear Proyecto',
            ]) ?>
        </div>
    </div>
    <hr />

    <div class="card">
        <div class="card-body">
            <?php Pjax::begin(['id' => 'portafolio-pjax']); ?>
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
                                        <?= $form->field($searchModel, 'soc_numero', ['template' => '{input}'])->textInput(['placeholder' => 'Código Socio', 'class' => 'form-control']) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form->field($searchModel, 'soc_nombre', ['template' => '{input}'])->textInput(['placeholder' => 'Nombre Socio', 'class' => 'form-control']) ?>
                                    </div>
                                   <div class="col-md-4">
                                        <?= $form->field($searchModel, 'por_titulo', ['template' => '{input}'])->textInput(['placeholder' => 'Título del Proyecto', 'class' => 'form-control']) ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?= Html::submitButton('<i class="bx bx-search-alt mr-1"></i> Buscar', ['class' => 'btn btn-success radius-30']) ?>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <?= GridView::widget([
                            'id' => 'portafolio-grid-view',
                            'dataProvider' => $dataProvider,
                            'summary' => false,
                            'tableOptions' => ['class' => 'tableData table mb-0 dataTable no-footer'],
                            'columns' => [
                                [
                                    'attribute' => 'soc_numero',
                                    'label' => 'Socio Código',
                                    'value' => 'soc.soc_numero',
                                ],
                                [
                                    'attribute' => 'soc_nombre',
                                    'label' => 'Nombre Socio',
                                    'value' => 'soc.soc_nombre',
                                ],
                                [
                                    'attribute' => 'por_titulo',
                                    'label' => 'Título del Proyecto',
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'label' => 'Fecha de Creación',
                                    'format' => ['date', 'php:d-m-Y'],
                                ],
                                [
                                    'class' => ActionColumn::class,
                                    'header' => 'Acciones',
                                    'template' => '<div class="d-inline-flex gap-1">{view}{update}{delete}</div>',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::a('<i class="bx bx-id-card"></i>', Url::toRoute(['view', 'por_id' => $model->por_id]), [
                                                'title' => 'Ver Proyecto',
                                                'class' => 'btn btn-light',
                                            ]);
                                        },
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<i class="bx bx-edit"></i>', Url::toRoute(['update', 'por_id' => $model->por_id]), [
                                                'title' => 'Editar Proyecto',
                                                'class' => 'btn btn-light',
                                            ]);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('<i class="bx bx-trash"></i>', Url::toRoute(['delete', 'por_id' => $model->por_id]), [
                                                'title' => 'Eliminar Proyecto',
                                                'class' => 'btn btn-light',
                                                'data-confirm' => '¿Está seguro de que desea eliminar este proyecto?',
                                                'data-method' => 'post',
                                                'data-pjax' => '1',
                                            ]);
                                        },
                                    ],
                                    'urlCreator' => fn($action, Portafolio $model, $key, $index, $column) =>
                                    Url::toRoute([$action, 'por_id' => $model->por_id]),
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
