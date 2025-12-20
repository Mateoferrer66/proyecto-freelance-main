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

$this->registerJs(
    <<<'JS'
$(function() {
    const series = {
        facturas: 'F',
        liquidacion: 'L',
        clientes: 'C',
        socios: 'S',
        presupuestos: 'P',
        liquidaciones: 'PL'
    };

    for (const [id, serie] of Object.entries(series)) {
        const inputElement = $(`#${id}`);
        const buttonElement = inputElement.closest('.row').find('button');

        if (inputElement.length && buttonElement.length) { // Ensure both input and button exist
            buttonElement.on('click', function() {
                const consecutivo = inputElement.val(); // Get value using jQuery
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
    }
});
JS
);
?>
<div class="consecutivo-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>
    <div class="page-content">


        <h6 class="mb-0 text-uppercase">CONSECUTIVOS</h6>
        <hr />
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-white">
                    <div class="card-body p-5">
                        <div id="respuestaForm">
                            <?php $url = Url::to(['consecutivo/set-consecutivo']); ?>
                            <div class="row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="facturas">Consecutivo de Facturas</label>
                                    <input type="text" id="facturas" placeholder="Ingrese consecutivo" class="form-control mb-3">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label></label>
                                    <button data-url="<?= $url ?>" class="btn btn-light">
                                        <i class="bx bx-plus"></i> Guardar
                                    </button>
                                </div>
                                <hr>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="liquidacion">Consecutivo de Liquidación</label>
                                    <input type="text" id="liquidacion" placeholder="Ingrese consecutivo" class="form-control mb-3">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label></label>
                                    <button data-url="<?= $url ?>" class="btn btn-light">
                                        <i class="bx bx-plus"></i> Guardar
                                    </button>
                                </div>
                                <hr>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="clientes">Consecutivo de Clientes</label>
                                    <input type="text" id="clientes" placeholder="Ingrese consecutivo" class="form-control mb-3">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label></label>
                                    <button data-url="<?= $url ?>" class="btn btn-light">
                                        <i class="bx bx-plus"></i> Guardar
                                    </button>
                                </div>
                                <hr>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="socios">Consecutivo de Socios</label>
                                    <input type="text" id="socios" placeholder="Ingrese consecutivo" class="form-control mb-3">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label></label>
                                    <button data-url="<?= $url ?>" class="btn btn-light">
                                        <i class="bx bx-plus"></i> Guardar
                                    </button>
                                </div>
                                <hr>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="presupuestos">Consecutivo de Presupuestos</label>
                                    <input type="text" id="presupuestos" placeholder="Ingrese consecutivo" class="form-control mb-3">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label></label>
                                    <button data-url="<?= $url ?>" class="btn btn-light">
                                        <i class="bx bx-plus"></i> Guardar
                                    </button>
                                </div>
                                <hr>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="liquidaciones">Consecutivo de Liquidaciones Provisionales</label>
                                    <input type="text" id="liquidaciones" placeholder="Ingrese consecutivo" class="form-control mb-3">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label></label>
                                    <button data-url="<?= $url ?>" class="btn btn-light">
                                        <i class="bx bx-plus"></i> Guardar
                                    </button>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>