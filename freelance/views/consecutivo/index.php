<?php

use app\models\Consecutivo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ConsecutivoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Consecutivos';
$this->params['breadcrumbs'] = [];

$this->registerJs(<<<'JS'
const series = {
    facturas: 'F',
    liquidacion: 'L',
    clientes: 'C',
    socios: 'S',
    presupuestos: 'P',
    liquidaciones: 'PL'
};

for (const [id, serie] of Object.entries(series)) {
    document.querySelector(`#${id} ~ button`).addEventListener('click', function() {
        const consecutivo = document.getElementById(id).value;
        if (!consecutivo) {
            alert('Ingrese un consecutivo');
            return;
        }
        fetch(this.dataset.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-Token': yii.getCsrfToken()
            },
            body: `serie=${serie}&consecutivo=${encodeURIComponent(consecutivo)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Consecutivo guardado');
                location.reload(); // Recargar la página para ver el cambio en el GridView
            } else {
                alert('Error: ' + (data.message || 'No se pudo guardar'));
            }
        });
    });
}
JS
);
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

        .consecutivo-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #2a2a3b;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .consecutivo-container h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #ffa500;
        }

        .consecutivo-form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .consecutivo-form-group label {
            flex: 1;
            font-size: 16px;
            margin-right: 10px;
        }

        .consecutivo-form-group input {
            flex: 2;
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        .consecutivo-form-group button {
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

        .consecutivo-form-group button:hover {
            background-color: #ff8c00;
        }
    </style>

    <div class="mb-3" style="margin-top:20px;">
        <?= Html::a('<i class="bx bx-plus-medical"></i> Crear Consecutivo', ['create'], [
            'class' => 'btn btn-success px-4 radius-30',
            'title' => 'Agregar nuevo consecutivo',
        ]) ?>
    </div>

    <div class="consecutivo-container">
        <h1>Consecutivos</h1>
        <?php $url = Url::to(['consecutivo/set-consecutivo']); ?>
        <div class="consecutivo-form-group">
            <label for="facturas">Consecutivo de Facturas</label>
            <input type="text" id="facturas" placeholder="Ingrese consecutivo">
            <button data-url="<?= $url ?>">Guardar</button>
        </div>
        <div class="consecutivo-form-group">
            <label for="liquidacion">Consecutivo de Liquidación</label>
            <input type="text" id="liquidacion" placeholder="Ingrese consecutivo">
            <button data-url="<?= $url ?>">Guardar</button>
        </div>
        <div class="consecutivo-form-group">
            <label for="clientes">Consecutivo de Clientes</label>
            <input type="text" id="clientes" placeholder="Ingrese consecutivo">
            <button data-url="<?= $url ?>">Guardar</button>
        </div>
        <div class="consecutivo-form-group">
            <label for="socios">Consecutivo de Socios</label>
            <input type="text" id="socios" placeholder="Ingrese consecutivo">
            <button data-url="<?= $url ?>">Guardar</button>
        </div>
        <div class="consecutivo-form-group">
            <label for="presupuestos">Consecutivo de Presupuestos</label>
            <input type="text" id="presupuestos" placeholder="Ingrese consecutivo">
            <button data-url="<?= $url ?>">Guardar</button>
        </div>
        <div class="consecutivo-form-group">
            <label for="liquidaciones">Consecutivo de Liquidaciones Provisionales</label>
            <input type="text" id="liquidaciones" placeholder="Ingrese consecutivo">
            <button data-url="<?= $url ?>">Guardar</button>
        </div>
    </div>

    <div class="consecutivo-container" style="margin-top: 40px;">
        <h1>Listado de Consecutivos</h1>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'con_serie',
                'con_consecutivo',
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Consecutivo $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'con_serie' => $model->con_serie, 'con_consecutivo' => $model->con_consecutivo]);
                    }
                ],
            ],
        ]); ?>
    </div>

</div>