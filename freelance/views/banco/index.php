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
$this->params['breadcrumbs']=[];
?>
<div class="banco-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>
    <p>
        <?= Html::a('Create Banco', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
        </div>
            <div class="col d-flex justify-content-between align-items-start">
            <h6 class="mb-0 text-uppercase">Bancos <dl>2</dl></h6>
        </div>

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
                'attribute' => 'ban_numcuenta',
                'label' => 'Acciones',
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Banco $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'ban_id' => $model->ban_id]);
                 }
            ],
        ],
    ]); ?>
</div>
