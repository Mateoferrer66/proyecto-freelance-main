<?php

use app\models\Iva;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\IvaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Utilidades Iva';
$this->registerCss(".table thead a { text-decoration: none !important; }");
$this->params['breadcrumbs']=[];?>
<div class="iva-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>


<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e2f;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #2a2a3b;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #ffa500;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            flex: 1;
            font-size: 16px;
            margin-right: 10px;
        }

        .form-group input {
            flex: 2;
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        .form-group button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #ffa500;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            text-transform: uppercase;
        }

        .form-group button:hover {
            background-color: #ff8c00;
        }
    </style>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

<div class="mb-3">
    <?= Html::a('<i class="bx bx-plus-medical"></i> Crear IVA', ['iva/create'], [
        'class' => 'btn btn-success px-4 radius-30',
        'title' => 'Agregar nuevo concepto de IVA',
        ]) ?>
    </div>
    
        </div>
            <div class="col d-flex justify-content-between align-items-start">
            <h6 class="mb-0 text-uppercase">IVA <dl>2</dl></h6>
        </div>

    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => false,
    'columns' => [
        [
            'attribute' => 'iva_porcentaje',
            'label' => 'IVA Porcentaje',
        ],
        [
            'attribute' => 'iva_concepto',
            'label' => 'IVA Concepto',
        ],
        [
            'class' => ActionColumn::class,
            'header' => 'Acciones', // 👈 Aquí defines el nombre de la columna
            'template' => '{update}',
            'urlCreator' => function ($action, $model, $key, $index, $column) {
                return Url::toRoute([$action, 'iva_id' => $model->iva_id]);
            },
            'buttons' => [
                'update' => function ($url, $model, $key) {
                    return Html::a('<i class="bx bx-pencil"></i>', $url, [
                        'title' => 'Editar',
                        'class' => 'btn btn-sm btn-outline-primary',
                    ]);
                },
            ],
        ],
    ],
]); ?>




</div>