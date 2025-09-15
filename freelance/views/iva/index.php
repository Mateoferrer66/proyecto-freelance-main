<?php

use app\models\Iva;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\IvaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Iva';
$this->registerCss(".table thead a { text-decoration: none !important; }");
$this->params['breadcrumbs'] = [];
?>
<div class="iva-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>



    <div class="mb-3">
        <?= Html::a('<i class="bx bx-plus-medical"></i> Crear IVA', ['iva/create'], [
            'class' => 'btn btn-success px-4 radius-30',
            'title' => 'Agregar nuevo concepto de IVA',
        ]) ?>
    </div>

    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => false,
    'columns' => [
        [
            'attribute' => 'iva_porcentaje',
            'label' => 'IVA Porcentaje',
        ],
        [
            'attribute' => 'iva_concepto',
            'label' => 'IVA Concepto',
        ],
        [
            'class' => ActionColumn::class,
            'header' => 'Acciones', // 👈 Aquí defines el nombre de la columna
            'template' => '{update}',
            'urlCreator' => function ($action, $model, $key, $index, $column) {
                return Url::toRoute([$action, 'iva_id' => $model->iva_id]);
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