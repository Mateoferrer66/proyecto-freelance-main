<?php

use app\models\TipoDocIdentidad;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TipoDocIdentidadSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tipo Doc Identidad';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-doc-identidad-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <p>
        <?= Html::a('Create Tipo Doc Identidad', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
      [
                'attribute' => 'tdo_id',
                'label' => 'Codigo',
            ],
            [
                'attribute' => 'tdo_nombre',
                'label' => 'Nombre',
            ],
            [
                'class' => ActionColumn::className(),
                                'header' => 'Acciones', // Agregar el encabezado "Acciones"

                'urlCreator' => function ($action, TipoDocIdentidad $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'tdo_id' => $model->tdo_id]);
                 }
            ],
        ],
    ]); ?>


</div>
