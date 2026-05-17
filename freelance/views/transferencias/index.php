<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Transferencias';
$this->params['breadcrumbs'] = [];
$this->registerCss(".table thead a { text-decoration: none !important; }");

?>
<div class="page-content">

    <div class="col d-flex justify-content-between align-items-start">
        <h6 class="mb-0 text-uppercase">Transferencias <dl>0</dl>
        </h6>
        <div>
            <?= Html::a('<i class="bx bx-plus mr-1"></i> Agregar Liquidación Socio', ['create-liquidacion-socio'], [
                'class' => 'btn btn-outline-warning radius-30 text-orange',
                'title' => 'Agregar Liquidación Socio',
            ]) ?>
            <?= Html::a('<i class="bx bx-plus mr-1"></i> Agregar Liquidación Freelance', ['create-liquidacion-freelance'], [
                'class' => 'btn btn-outline-warning radius-30 text-orange',
                'title' => 'Agregar Liquidación Freelance',
            ]) ?>
            <?= Html::a('<i class="bx bx-plus mr-1"></i> Agregar', ['create'], [
                'class' => 'btn btn-outline-warning radius-30 text-orange',
                'title' => 'Agregar',
            ]) ?>
        </div>
    </div>
    <hr />

    <div class="card bg-transparent shadow-none border-0">
        <div class="card-body p-0">
            <?php Pjax::begin(['id' => 'transferencias-pjax']); ?>
            <div class="col-xl-12 mx-auto">
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-4">
                                <form method="get" class="search-bar mb-3" data-pjax="1" id="auto-search-form">
                                    <div class="row mb-1">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="codigo_socio" placeholder="Código Socio" />
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="nombre_socio" placeholder="Nombre Socio" />
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="numero_liquidacion" placeholder="Número Liquidación" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="date" class="form-control datepicker" name="fecha_desde" placeholder="Fecha inicial" />
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control datepicker" name="fecha_hasta" placeholder="Fecha final" />
                                        </div>
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-outline-warning radius-30 text-orange">
                                                <i class="bx bx-search-alt"></i>
                                            </button>
                                        </div>
                                        <div class="col-md-5 d-flex justify-content-end">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <!-- Action buttons on the left like Excel, PDF, Print -->
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <?= Html::button('<i class="bx bx-trash"></i> ELIMINAR', [
                                'class' => 'btn text-orange radius-30',
                                'id' => 'batch-delete-button'
                            ]) ?>
                            <?= Html::a('<i class="bx bx-list-ol mr-1"></i>Listado Liquidaciones', ['index'], ['class' => 'btn text-orange radius-30']) ?>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <?= GridView::widget([
                            'id' => 'transferencias-grid-view',
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
                                    'header' => '# Liquidación',
                                    'value' => function ($model) { return ''; },
                                ],
                                [
                                    'header' => 'Nombre',
                                    'value' => function ($model) { return ''; },
                                ],
                                [
                                    'header' => 'Importe',
                                    'value' => function ($model) { return ''; },
                                ],
                                [
                                    'header' => 'Fecha',
                                    'value' => function ($model) { return ''; },
                                ],
                                [
                                    'header' => 'Acciones',
                                    'headerOptions' => ['class' => 'text-center'],
                                    'contentOptions' => ['class' => 'text-center'],
                                    'value' => function ($model) { return ''; },
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
