<?php

use app\models\Banco;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BancoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestión de Bancos';
$this->params['breadcrumbs'] = []; ?>
<div class="banco-index">

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

    <p>
        <?= Html::a('Imprimir', ['iva/print'], ['target' => '_blank']) ?>
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
            width: 300px;
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
      <div class="mb-3" style="margin-top:20px;">
        <?= Html::a('<i class="bx bx-plus-medical"></i> Crear Banco', ['create'], [
            'class' => 'btn btn-success px-4 radius-30',
            'title' => 'Agregar nuevo Banco',
        ]) ?>
    </div>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestión de Bancos</h1>
            <div class="search-bar">
                <input type="text" placeholder="Buscar banco...">
            </div>
        </div>

        <div class="buttons">
            <button>Excel</button>
            <button>PDF</button>
            <button>Print</button>
        </div>

        <!-- <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>BN001</td>
                        <td>Banco Nacional</td>
                        <td>
                            <a href="/banco/update?id=1" style="background-color: #ffa500; padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">Editar</a>
                            <a href="/banco/delete?id=1" style="background-color: #ff4d4d; padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">Eliminar</a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>BN002</td>
                        <td>Banco Internacional</td>
                        <td>
                            <a href="/banco/update?id=2" style="background-color: #ffa500; padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">Editar</a>
                            <a href="/banco/delete?id=2" style="background-color: #ff4d4d; padding: 5px 10px; border-radius: 5px; color: #fff; text-decoration: none;">Eliminar</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> -->
    </div>
</body>
<div class="col d-flex justify-content-between align-items-start">
    <h6 class="mb-0 text-uppercase">
        Bancos <span class="badge bg-warning text-dark"><?= $dataProvider->getTotalCount() ?></span>
    </h6>
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
                'label' => 'Nombre',
            ],
            [
                'class' => ActionColumn::class,
                'header' => 'Acciones',
                'urlCreator' => function ($action, Banco $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'ban_id' => $model->ban_id]);
                }
            ],
        ],
    ]); ?>

</div>