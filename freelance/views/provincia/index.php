<?php

use app\models\Provincia;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ProvinciaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Utilidades Provincia';
$this->registerCss(".table thead a { text-decoration: none !important; }");
$this->params['breadcrumbs'] = [];
?>

<div class="provincia-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="mb-3">
        <?= Html::a('<i class="bx bx-plus-medical"></i> Crear Provincia', ['provincia/create'], [
            'class' => 'btn btn-success px-4 radius-30',
            'title' => 'Agregar nueva provincia',
        ]) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'pager' => [
            'firstPageLabel' => 'Primera',
            'lastPageLabel' => 'Última',
            'prevPageLabel' => '←',
            'nextPageLabel' => '→',
            'maxButtonCount' => 5,
            'options' => ['class' => 'pagination justify-content-center'],
        ],
        'columns' => [
            [
                'attribute' => 'prv_id',
                'label' => 'Código',
            ],
            [
                'attribute' => 'pai_id',
                'label' => 'País',
            ],
            [
                'attribute' => 'pai_id',
                'label' => 'Nombre País',
                'value' => function ($model) {
                    return $model->pais ? $model->pais->pai_nombre : '(sin país)';
                },
            ],
            [
                'attribute' => 'prv_nombre',
                'label' => 'Nombre',
            ],
            [
                'attribute' => 'prv_eliminada',
                'label' => '¿Eliminada?',
                'value' => function ($model) {
                    return $model->prv_eliminada ? 'Sí' : 'No';
                },
            ],
            [
                'class' => ActionColumn::class,
                'header' => 'Acciones',
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'prv_id' => $model->prv_id]);
                },
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="bx bx-pencil"></i>', $url, [
                            'title' => 'Editar',
                            'class' => 'btn btn-sm btn-outline-primary me-1',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="bx bx-trash"></i>', $url, [
                            'title' => 'Eliminar',
                            'class' => 'btn btn-sm btn-outline-danger',
                            'data-confirm' => '¿Estás seguro de que deseas eliminar esta provincia?',
                            'data-method' => 'post',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>