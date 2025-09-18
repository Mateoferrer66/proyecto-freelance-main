<?php

use app\models\FormaDePago;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\FormaDePagoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Forma De Pagos';
$this->params['breadcrumbs']=[];
?>
<div class="forma-de-pago-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <p>
        <?= Html::a('Create Forma De Pago', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
        </div>
            <div class="col d-flex justify-content-between align-items-start">
            <h6 class="mb-0 text-uppercase">Formas de Pagos <dl>2</dl></h6>
        </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'fdp_id',
                'label' => 'Codigo',
            ],
            [
                'attribute' => 'fdp_nombre',
                'label' => 'Nombre',
            ],
            [
                'class' => ActionColumn::className(),
                'header' => 'Acciones', // Agregar el encabezado "Acciones"
                'urlCreator' => function ($action, FormaDePago $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'fdp_id' => $model->fdp_id]);
                    }
            ],
        ],
    ]); ?>
</div>