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

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    
        </div>
            <div class="col d-flex justify-content-between align-items-start">
            <h6 class="mb-0 text-uppercase">Paises y Provincias <dl>2</dl></h6>
        </div>

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
                'attribute' => 'pai_id',
                'label' => 'Código',
            ],
            [
                'attribute' => 'pai_nombre',
                'label' => 'País',
                'value' => function ($model) {
                    return $model->pais ? $model->pais->pai_nombre : null; // Asegúrate de que la relación esté cargada
                },
            ],
            [
                'attribute' => 'prv_nombre',
                'label' => 'Provincia',
            ],
            [
                'class' => ActionColumn::className(),
                'header' => 'Acciones', // Agregar el encabezado "Acciones"
                'urlCreator' => function ($action, Provincia $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'pai_id' => $model->pai_id]);
                    }
            ],
        ],
    ]); ?>

</div>