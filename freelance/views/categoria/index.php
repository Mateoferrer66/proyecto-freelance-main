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

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <div class="mb-3">
        <?= Html::a('<i class="bx bx-plus-medical"></i> Crear Categoría', ['categoria/create'], [
            'class' => 'btn btn-success px-4 radius-30',
            'title' => 'Agregar nueva categoría',
        ]) ?>
    </div>

    
        </div>
            <div class="col d-flex justify-content-between align-items-start">
            <h6 class="mb-0 text-uppercase">Categorias Profesionales<dl>2</dl></h6>
        </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
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
                'class' => ActionColumn::className(),
                'header' => 'Acciones', // Agregar el encabezado "Acciones"
                'urlCreator' => function ($action, Categoria $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'cat_id' => $model->cat_id]);
                    }
            ],
        ],
    ]); ?>

</div>