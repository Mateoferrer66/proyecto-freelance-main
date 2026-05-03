<?php
use yii\helpers\Html;
use app\models\TipoDocIdentidad;
use app\models\Pais;
use app\models\Provincia;
use app\components\UtilitiesHelper;
?>
<div class="row">
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Número Socio</b></h6>
        <?= $model->soc_numero?>
    </div>
    <div class="col-md-12">
        <h4><b>DATOS PERSONALES</b></h4>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Nombre</b></h6>
        <?= $model->soc_nombre?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Primer Apellido</b></h6>
        <?= $model->soc_apellido1?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Segundo Apellido</b></h6>
        <?= $model->soc_apellido2?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Sexo</b></h6>
         <?= $model->soc_sexo?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Tipo Documento</b></h6>
        <?= $model->tdo->tdo_nombre?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Número Documento</b></h6>
        <?= $model->soc_numdocide?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Categoría</b></h6>
        <?= $model->categoria->cat_nombre?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Ocupación</b></h6>
        <?= $model->soc_ocupacion?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Fecha de nacimiento</b></h6>
        <?= UtilitiesHelper::db2date($model->soc_fecnacimiento)?>
    </div>
    <div class="col-md-12">
        <h4><b>DATOS DE CONTACTO</b></h4>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Teléfono fijo</b></h6>
        <?= $model->soc_telfijo?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Móvil</b></h6>
        <?= $model->soc_telmovil?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Domicilio</b></h6>
        <?= $model->soc_direccion?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Provincia</b></h6>
        <?= $model->provincia->prv_nombre?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Población</b></h6>
        <?= $model->soc_poblacion?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Código postal</b></h6>
        <?= $model->soc_codpostal?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>E-mail</b></h6>
        <?= $model->soc_email?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Web</b></h6>
        <?= $model->soc_web?>
    </div>
    <div class="col-md-12">
        <h4><b>DATOS DE FACTURACIÓN</b></h4>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Número seguridad social</b></h6>
        <?= $model->soc_numsegsocial?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Grupo cotización seguridad social</b></h6>
        <?= $model->soc_grcotsegsocial?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Coeficiente de cotización</b></h6>
        <?= $model->soc_coefcotizacion?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Base de cotización</b></h6>
        <?= $model->soc_basecotizacion?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Cuenta bancaria</b></h6>
        <?= $model->soc_ctabancaria?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Porcentaje IRPF</b></h6>
        <?= $model->soc_porcretirpf?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Participaciones (Desde)</b></h6>
        <?= $model->soc_participacion_desde?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Participaciones (Hasta)</b></h6>
        <?= $model->soc_participacion_hasta?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Deuda</b></h6>
        <?= $model->soc_deuda?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Estado</b></h6>
        <?= $model->soc_estado?>
    </div>
    <div class="col-md-12">
        <h4><b>DOCUMENTOS</b></h4>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Logo</b></h6>
        <?= $model->soc_ficlogo?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Contrato</b></h6>
        <?= $model->soc_ficcontrato?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Documento identidad</b></h6>
        <?= $model->soc_ficdocide?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>Otros Documentos</b></h6>
        <?= $model->soc_ficotros?>
    </div>
    <div class="col-md-6 mb-2 text-white">
        <h6><b>PRL</b></h6>
        <?= $model->soc_fiprl?>
    </div>
    <div class="col-md-12 text-white">
        <h4><b>OBSERVACIONES</b></h4>
        <?= $model->soc_observaciones?>
    </div>
</div>