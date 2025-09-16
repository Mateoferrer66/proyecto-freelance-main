<?php

use app\models\ConceptoFacturacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Concepto Facturacion';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="concepto-facturacion-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <p>
        <?= Html::a('Create Concepto Facturacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'cof_codigo',
                'label' => 'Codigo',
            ],
            [
                'attribute' => 'cof_nombre',
                'label' => 'Nombre',
            ],
            [
                'class' => ActionColumn::className(),
                'header' => 'Acciones', // Agregar el encabezado "Acciones"
                'urlCreator' => function ($action, ConceptoFacturacion $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'cof_id' => $model->cof_id]);
                    }
            ],
        ],
    ]); ?>

</div>