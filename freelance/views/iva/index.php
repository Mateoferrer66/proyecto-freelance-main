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
$this->params['breadcrumbs'] = []; ?>
<div class="iva-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <p>
        <?= Html::a('Exportar Excel', ['iva/export-excel'], [
            'target' => '_blank'
        ]) ?>
    </p>

    <p>
        <?= Html::a('Exportar PDF', ['iva/export-pdf'], [
            'target' => '_blank'
        ]) ?>
    </p>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e2f;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #2a2a3b;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            text-transform: uppercase;
            color: #ffa500;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-bar input {
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            width: 200px;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #ffa500;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            text-transform: uppercase;
        }

        .buttons button:hover {
            background-color: #ff8c00;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #333;
            color: #fff;
            border-radius: 5px;
            overflow: hidden;
        }

        table thead {
            background-color: #444;
        }

        table thead th {
            padding: 10px;
            text-align: left;
        }

        table tbody td {
            padding: 10px;
            border-bottom: 1px solid #444;
        }

        table tbody tr:hover {
            background-color: #2a2a3b;
        }
    </style>
    </head>

    <body>
        <div class="container">
            <div class="header">
                <h1>Gestión de IVA</h1>
                <div class="search-bar">
                    <input type="text" placeholder="Buscar...">
                </div>
            </div>

            <div class="buttons">
                <button>Excel</button>
                <button>PDF</button>
                <button>Print</button>
            </div>

        </div>
    </body>
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
    <h6 class="mb-0 text-uppercase">IVA <dl>2</dl>
    </h6>
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
            'class' => ActionColumn::className(),
            'header' => 'Acciones', // Agregar el encabezado "Acciones"
            'urlCreator' => function ($action, Iva $model, $key, $index, $column) {
                return Url::toRoute([$action, 'iva_id' => $model->iva_id]);
            }
        ],
    ],
]); ?>




</div>