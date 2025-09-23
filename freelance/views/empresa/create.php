<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TipoDocIdentidad;

/** @var yii\web\View $this */
/** @var app\models\Empresa $empresa */
/** @var app\models\Configuracion $configuracion */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Datos de la Empresa';
$this->params['breadcrumbs'] = [];
?>

<?= $this->render('@app/views/layouts/_orangemenu') ?>

<div class="page-content">

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

    <h6 class="mb-0 text-uppercase">DATOS DE LA EMPRESA</h6>
    <hr />
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body p-5">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <?= $form->field($empresa, 'emp_razon_social')->textInput(['maxlength' => true])->label('Nombre/Razón Social') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($empresa, 'tdo_id')->dropDownList(
                                ArrayHelper::map(TipoDocIdentidad::find()->all(), 'tdo_id', 'tdo_nombre'),
                                ['prompt' => 'Seleccione']
                            )->label('Tipo Documento *') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($empresa, 'emp_numdocide')->textInput(['maxlength' => true])->label('Número identificación Fiscal') ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="card-title d-flex align-items-center mt-3">
                            <h5 class="mb-0 text-white">DATOS DE CONTACTO</h5>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <?= $form->field($empresa, 'emp_telefono')->textInput(['maxlength' => true])->label('Teléfono*') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($empresa, 'emp_fax')->textInput(['maxlength' => true])->label('Fax') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($empresa, 'emp_direccion')->textInput(['maxlength' => true])->label('Dirección*') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($empresa, 'emp_poblacion')->textInput(['maxlength' => true])->label('Población') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($empresa, 'emp_codpostal')->textInput(['maxlength' => true])->label('Código postal') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($empresa, 'emp_email')->textInput(['maxlength' => true])->label('E-mail *') ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="card-title d-flex align-items-center">
                            <h5 class="mb-0 text-white">DATOS SEGURIDAD SOCIAL</h5>
                        </div>
                        <hr>
                        <div class="col-md-4">
                            <?= $form->field($empresa, 'emp_regimen_segs')->textInput(['maxlength' => true])->label('Régimen (seguridad social)*') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($empresa, 'emp_ccc_segs')->textInput(['maxlength' => true])->label('CCC (seguridad social)*') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($empresa, 'emp_tipo_segs')->textInput(['maxlength' => true])->label('Tipo de empresa (seguridad social)*') ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <?= $form->field($configuracion, 'con_base_cotizacion_ss')->textInput()->label('Base cotización (€)*') ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <?= $form->field($empresa, 'emp_razons_segs')->textInput(['maxlength' => true])->label('Razón Social (seguridad social)*') ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="card-title d-flex align-items-center mt-3">
                            <h5 class="mb-0 text-white">OTROS</h5>
                        </div>
                        <hr>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <?= $form->field($configuracion, 'con_retencion_imp_soc')->textInput()->label('Retención a cuenta impto/sdades (%)') ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <?= $form->field($empresa, 'emp_participaciones')->textInput()->label('Participaciones') ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success px-5 radius-30']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>