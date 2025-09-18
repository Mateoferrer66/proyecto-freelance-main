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
$this->params['breadcrumbs'] = [];
?>
<div class="tipo-doc-identidad-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <p>
        <?= Html::a('Create Tipo Doc Identidad', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    
        </div>
            <div class="col d-flex justify-content-between align-items-start">
            <h6 class="mb-0 text-uppercase">Tipos de documentos de identidad <dl>2</dl></h6>
        </div>

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
    'class' => ActionColumn::class, // Reemplazar className() con ::class
    'header' => 'Acciones', // Agregar el encabezado "Acciones"
    'urlCreator' => function ($action, TipoDocIdentidad $model, $key, $index, $column) {
        return Url::toRoute([$action, 'tdo_id' => $model->tdo_id]);
    }
],
        ],
    ]);

    ?>

</div>