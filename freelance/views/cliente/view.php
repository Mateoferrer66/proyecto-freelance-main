<?php

use yii\helpers\Html;
use yii\widgets\DetailView; // Aunque no lo usaremos directamente, lo mantengo por si acaso
use app\models\TipoDocIdentidad;
use app\models\Pais;
use app\models\Provincia;
use app\assets\PanelAsset; // Añadido

/** @var yii\web\View $this */
/** @var app\models\Cliente $model */

$this->title = 'Detalles del Cliente: ' . $model->cli_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
PanelAsset::register($this); // Añadido
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
                            <strong>Número Cliente:</strong> <?= Html::encode($model->cli_numero) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Tipo Documento:</strong> <?= Html::encode($model->tdo->tdo_nombre ?? 'N/A') ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Número Identificación Fiscal:</strong> <?= Html::encode($model->cli_numdocide) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nombre Razón Social:</strong> <?= Html::encode($model->cli_nombre) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Persona de Contacto:</strong> <?= Html::encode($model->cli_persona_contacto) ?>
                        </div>
                    </div>

                    <?php if ($model->cli_docinipais || $model->cli_feccaddoc): ?>
                    <div class="row mb-3">
                        <?php if ($model->cli_docinipais): ?>
                        <div class="col-md-4">
                            <strong>Iniciales País:</strong> <?= Html::encode($model->cli_docinipais) ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($model->cli_feccaddoc): ?>
                        <div class="col-md-4">
                            <strong>Fecha Caducidad Documento:</strong> <?= Html::encode($model->cli_feccaddoc) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">DATOS DE CONTACTO</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Teléfono 1:</strong> <?= Html::encode($model->cli_tel1) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Teléfono 2:</strong> <?= Html::encode($model->cli_tel2) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Email:</strong> <?= Html::encode($model->cli_email) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Dirección:</strong> <?= Html::encode($model->cli_direccion) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Población:</strong> <?= Html::encode($model->cli_poblacion) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>País:</strong> <?= Html::encode($model->pais->pai_nombre ?? 'N/A') ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Provincia:</strong> <?= Html::encode($model->provincia->prv_nombre ?? 'N/A') ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Código Postal:</strong> <?= Html::encode($model->cli_codpostal) ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">OTROS DATOS</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Cuenta Contable:</strong> <?= Html::encode($model->cli_cuenta_contable) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>IVA:</strong> <?= Html::encode($model->iva->iva_nombre ?? 'N/A') ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Forma de Pago:</strong> <?= Html::encode($model->formaDePago->fdp_nombre ?? 'N/A') ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Observaciones:</strong> <?= nl2br(Html::encode($model->cli_observaciones)) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Estado:</strong> <?= Html::encode($model->cli_estado) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Exportado:</strong> <?= Html::encode($model->cli_exportado ? 'Sí' : 'No') ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Eliminado:</strong> <?= Html::encode($model->cli_eliminado ? 'Sí' : 'No') ?>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <?= Html::a('Actualizar', ['update', 'cli_id' => $model->cli_id], ['class' => 'btn btn-success px-5 radius-30']) ?>
                        <?= Html::a('Eliminar', ['delete', 'cli_id' => $model->cli_id], [
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