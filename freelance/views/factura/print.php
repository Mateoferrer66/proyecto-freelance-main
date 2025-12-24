<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Factura $model */

$this->title = 'Factura: ' . $model->fac_numero;
?>
<div class="factura-print">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-globe"></i> <?= Html::encode($this->title) ?>
                <small class="pull-right">Fecha: <?= Yii::$app->formatter->asDate($model->fac_fecha, 'php:d/m/Y') ?></small>
            </h2>
        </div>
    </div>

    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            De
            <address>
                <strong><?= Html::encode($model->soc->soc_nombre) ?></strong><br>
                <?= Html::encode($model->soc->soc_direccion) ?><br>
                Teléfono: <?= Html::encode($model->soc->soc_telmovil) ?><br>
                Email: <?= Html::encode($model->soc->soc_email) ?>
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            A
            <address>
                <strong><?= Html::encode($model->cli->cli_nombre) ?></strong><br>
                <?= Html::encode($model->cli->cli_direccion) ?><br>
                Teléfono: <?= Html::encode($model->cli->cli_tel1) ?><br>
                Email: <?= Html::encode($model->cli->cli_email) ?>
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            <b>Factura #<?= Html::encode($model->fac_numero) ?></b><br>
            <br>
            <b>Forma de Pago:</b> <?= Html::encode($model->fdp->fdp_nombre) ?><br>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($model->detalleFacturas as $detalle): ?>
                        <tr>
                            <td><?= $detalle->dtf_cantidad ?></td>
                            <td><?= Html::encode($detalle->cof->cof_nombre) ?></td>
                            <td><?= Html::encode($detalle->dtf_descripcion) ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($detalle->dtf_subtotal) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <p class="lead">Observaciones:</p>
            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                <?= nl2br(Html::encode($model->fac_observaciones)) ?>
            </p>
        </div>
        <div class="col-xs-6">
            <p class="lead">Total a pagar</p>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td><?= Yii::$app->formatter->asCurrency($model->fac_subtotal) ?></td>
                    </tr>
                    <tr>
                        <th>IVA:</th>
                        <td><?= Yii::$app->formatter->asCurrency($model->fac_iva) ?></td>
                    </tr>
                    <tr>
                        <th>Gastos Suplidos:</th>
                        <td><?= Yii::$app->formatter->asCurrency($model->fac_gastos_suplidos) ?></td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td><?= Yii::$app->formatter->asCurrency($model->fac_total) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    window.print();
</script>
