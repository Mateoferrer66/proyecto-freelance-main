<?php

use yii\helpers\Html;
use app\assets\PanelAsset;
use app\models\Cliente;
use app\models\Socio;
use app\models\FormaDePago;

/** @var yii\web\View $this */
/** @var app\models\Factura $model */

$this->title = 'Detalles de la Factura: ' . $model->fac_numero;
$this->params['breadcrumbs'][] = ['label' => 'Facturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
PanelAsset::register($this);
?>

<div class="page-content" style="margin-top: 3.4rem;">
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
                            <strong>Número Factura:</strong> <?= Html::encode($model->fac_numero) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Fecha:</strong> <?= Html::encode($model->fac_fecha) ?>
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
                            <strong>Subtotal:</strong> <?= Yii::$app->formatter->asCurrency($model->fac_subtotal) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>IVA:</strong> <?= Yii::$app->formatter->asCurrency($model->fac_iva) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Gastos Suplidos:</strong> <?= Yii::$app->formatter->asCurrency($model->fac_gastos_suplidos) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Total:</strong> <?= Yii::$app->formatter->asCurrency($model->fac_total) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Moneda:</strong> <?= Html::encode($model->fac_money) ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">ESTADO Y SITUACIÓN</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Estado:</strong> <?= Html::encode($model->fac_estado) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Situación:</strong> <?= Html::encode($model->fac_situacion) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Fecha Situación:</strong> <?= Html::encode($model->fac_fecha_situacion) ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">OTROS DATOS</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Observaciones:</strong> <?= nl2br(Html::encode($model->fac_observaciones)) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Logo:</strong> <?= Html::encode($model->fac_logo) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Idioma:</strong> <?= Html::encode($model->fac_language) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Exportada:</strong> <?= Html::encode($model->fac_exportada ? 'Sí' : 'No') ?>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <?= Html::a('Actualizar', ['update', 'fac_id' => $model->fac_id], ['class' => 'btn btn-success px-5 radius-30']) ?>
                        <?= Html::a('Eliminar', ['delete', 'fac_id' => $model->fac_id], [
                            'class' => 'btn btn-success px-5 radius-30',
                            'data' => [
                                'confirm' => '¿Está seguro de que desea eliminar este elemento?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= Html::button('Enviar por Correo', [
                            'class' => 'btn btn-primary px-5 radius-30',
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#sendEmailModal'
                        ]) ?>
                    </div>

                    <!-- Modal para enviar correo -->
                    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="sendEmailModalLabel">Enviar Factura por Correo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?= $this->render('_send_email_form', ['model' => $model]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
