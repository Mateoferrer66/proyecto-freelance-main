<?php

use app\models\ConceptoLiquidacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ConceptoLiquidacionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Concepto Liquidacion';
$this->params['breadcrumbs']=[];
?>
<div class="concepto-liquidacion-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <p>
        <?= Html::a('Create Concepto Liquidacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
        </div>
            <div class="col d-flex justify-content-between align-items-start">
            <h6 class="mb-0 text-uppercase">Concepto Liquidacion <dl>2</dl></h6>
        </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'col_id',
                'label' => 'Codigo',
            ],
            [
                'attribute' => 'col_nombre',
                'label' => 'Nombre',
            ],
            [
                'class' => ActionColumn::className(),
                'header' => 'Acciones', // Agregar el encabezado "Acciones"
                'urlCreator' => function ($action, ConceptoLiquidacion $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'cof_id' => $model->col_id]);
                    }
            ],
        ],
    ]); ?>

</div>
