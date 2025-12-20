<?php

use yii\helpers\Html;
use app\assets\PanelAsset;
use app\models\Cliente;
use app\models\Socio;
use app\models\FormaDePago;

/** @var yii\web\View $this */
/** @var app\models\Presupuesto $model */

$this->title = 'Detalles del Presupuesto: ' . $model->pre_numero;
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
PanelAsset::register($this);
?>

<div class="page-content">
    <h6 class="mb-0 text-uppercase"><?= Html::encode($this->title) ?></h6>
    <hr/>
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body p-5">
                    <div class="card-title d-flex align-items-center">
                        <h5 class="mb-0 text-white">INFORMACIÓN GENERAL</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Número Presupuesto:</strong> <?= Html::encode($model->pre_numero) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Fecha:</strong> <?= Html::encode($model->pre_fecha) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Cliente:</strong> <?= Html::encode($model->cli->cli_nombre ?? 'N/A') ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Socio:</strong> <?= Html::encode($model->soc->soc_nombre ?? 'N/A') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Forma de Pago:</strong> <?= Html::encode($model->fdp->fdp_nombre ?? 'N/A') ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">DATOS FINANCIEROS</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Subtotal:</strong> <?= Yii::$app->formatter->asCurrency($model->pre_subtotal) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>IVA:</strong> <?= Yii::$app->formatter->asCurrency($model->pre_iva) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Gastos Suplidos:</strong> <?= Yii::$app->formatter->asCurrency($model->pre_gastos_suplidos) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Total:</strong> <?= Yii::$app->formatter->asCurrency($model->pre_total) ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">OTROS DATOS</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Observaciones:</strong> <?= nl2br(Html::encode($model->pre_observaciones)) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Logo:</strong> <?= Html::encode($model->pre_logo) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Idioma:</strong> <?= Html::encode($model->pre_language) ?>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <?= Html::a('Actualizar', ['update', 'pre_id' => $model->pre_id], ['class' => 'btn btn-success px-5 radius-30']) ?>
                        <?= Html::a('Eliminar', ['delete', 'pre_id' => $model->pre_id], [
                            'class' => 'btn btn-success px-5 radius-30',
                            'data' => [
                                'confirm' => '¿Está seguro de que desea eliminar este elemento?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>