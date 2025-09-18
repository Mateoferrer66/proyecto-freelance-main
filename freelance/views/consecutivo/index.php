<?php

use app\models\Consecutivo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ConsecutivoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Consecutivos';
$this->registerCss(".table thead a { text-decoration: none !important; }");
$this->params['breadcrumbs'] = [];
?>
<div class="consecutivo-index">

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
</head>
<body>
    <div class="container">
        <h1>Consecutivos</h1>
        <div class="form-group">
            <label for="facturas">Consecutivo de Facturas</label>
            <input type="text" id="facturas" placeholder="Ingrese consecutivo">
            <button>Agregar</button>
        </div>
        <div class="form-group">
            <label for="liquidacion">Consecutivo de Liquidación</label>
            <input type="text" id="liquidacion" placeholder="Ingrese consecutivo">
            <button>Agregar</button>
        </div>
        <div class="form-group">
            <label for="clientes">Consecutivo de Clientes</label>
            <input type="text" id="clientes" placeholder="Ingrese consecutivo">
            <button>Agregar</button>
        </div>
        <div class="form-group">
            <label for="socios">Consecutivo de Socios</label>
            <input type="text" id="socios" placeholder="Ingrese consecutivo">
            <button>Agregar</button>
        </div>
        <div class="form-group">
            <label for="presupuestos">Consecutivo de Presupuestos</label>
            <input type="text" id="presupuestos" placeholder="Ingrese consecutivo">
            <button>Agregar</button>
        </div>
        <div class="form-group">
            <label for="liquidaciones">Consecutivo de Liquidaciones Provisionales</label>
            <input type="text" id="liquidaciones" placeholder="Ingrese consecutivo">
            <button>Agregar</button>
        </div>
    </div>
</body>

</div>
