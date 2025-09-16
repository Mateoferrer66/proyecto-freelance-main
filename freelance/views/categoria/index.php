<?php

use app\models\Categoria;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CategoriaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Categorías Profesionales';
$this->registerCss(".table thead a { text-decoration: none !important; }");
$this->params['breadcrumbs'] = [];
?>

<div class="categoria-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="mb-3">
        <?= Html::a('<i class="bx bx-plus-medical"></i> Crear Categoría', ['categoria/create'], [
            'class' => 'btn btn-success px-4 radius-30',
            'title' => 'Agregar nueva categoría',
        ]) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'columns' => [
            [
                'attribute' => 'cat_id',
                'label' => 'ID',
            ],
            [
                'attribute' => 'cat_nombre',
                'label' => 'Nombre',
            ],
            [
                'attribute' => 'cat_eliminada',
                'label' => '¿Eliminada?',
                'value' => function ($model) {
                    return $model->cat_eliminada ? 'Sí' : 'No';
                },
            ],
            [
                'class' => ActionColumn::class,
                'header' => 'Acciones',
                'template' => '{update}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'cat_id' => $model->cat_id]);
                },
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="bx bx-pencil"></i>', $url, [
                            'title' => 'Editar',
                            'class' => 'btn btn-sm btn-outline-primary',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>