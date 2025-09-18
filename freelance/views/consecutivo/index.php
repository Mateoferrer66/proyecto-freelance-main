<?php

use app\models\Consecutivo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ConsecutivoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Consecutivos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consecutivo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Consecutivo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'con_serie',
            'con_consecutivo',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Consecutivo $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'con_serie' => $model->con_serie, 'con_consecutivo' => $model->con_consecutivo]);
                 }
            ],
        ],
    ]); ?>


</div>
