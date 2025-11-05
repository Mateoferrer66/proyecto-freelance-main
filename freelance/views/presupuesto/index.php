<?php

use app\models\Presupuesto;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\PresupuestoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Presupuestos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presupuesto-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Presupuesto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pre_id',
            'pre_numero',
            'pre_logo',
            'pre_fecha',
            'pre_language',
            //'cli_id',
            //'soc_id',
            //'fdp_id',
            //'pre_subtotal',
            //'pre_iva',
            //'pre_gastos_suplidos',
            //'pre_total',
            //'pre_observaciones:ntext',
            //'pre_eliminado',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Presupuesto $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'pre_id' => $model->pre_id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
