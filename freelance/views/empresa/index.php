<?php

use app\models\Empresa;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\EmpresaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Empresas';
$this->params['breadcrumbs'] = [];
$this->registerCss(".table thead a { text-decoration: none !important; }");

?>
<div class="empresa-index">


        <?= $this->render('@app/views/layouts/_orangemenu') ?>


    <p>
        <?= Html::a('Create Empresa', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'emp_id',
            'emp_razon_social',
            'tdo_id',
            'emp_numdocide',
            'emp_direccion',
            //'emp_codpostal',
            //'emp_poblacion',
            //'emp_telefono',
            //'emp_fax',
            //'emp_email:email',
            //'emp_regimen_segs',
            //'emp_ccc_segs',
            //'emp_tipo_segs',
            //'emp_razons_segs',
            //'emp_participaciones',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Empresa $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'emp_id' => $model->emp_id]);
                 }
            ],
        ],
    ]); ?>


</div>
