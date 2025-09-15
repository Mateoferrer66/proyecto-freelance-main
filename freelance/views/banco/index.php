<?php

use app\models\Banco;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BancoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bancos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banco-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Banco', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'ban_id',
                'label' => 'Codigo',
            ],
            [
                'attribute' => 'ban_nombre',
                'label' => 'Banco',
            ],
            [
                'attribute' => 'ban_numcuenta',
                'label' => '#_cuenta',
            ],
            [
                'class' => ActionColumn::className(),
                'header' => 'Acciones', //
                'urlCreator' => function ($action, Banco $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'ban_id' => $model->ban_id]);
                 }
            ],
        ],
    ]); ?>


</div>
