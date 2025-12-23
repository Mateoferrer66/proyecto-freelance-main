<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
$this->title = 'Pegar token de restablecimiento';
?>

<div class="section-authentication-signin loginFlex">
    <div class="logoLogin">
        <img src="/assets-custom/images/logotipo.svg" alt="smartsy" />
        <div id="animate-area" style="background-image: url(/assets-custom/images/bg-themes/bcloud.jpg);"></div>
    </div>
    <div class="card card-login">
        <div class="text-center">
            <h3 class="">Pegar token</h3>
            <p>Si el link que recibiste se rompió, pega aquí el token completo</p>
        </div>
        <div class="form-body">
            <?php $form = ActiveForm::begin(['id' => 'enter-token-form']); ?>
            <?= Html::textarea('token', '', ['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Pega aquí el token o la URL completa recibida en el email']) ?>
            <div class="col-12 mt-3">
                <div class="d-grid">
                    <?= Html::submitButton('Continuar', ['class' => 'btn btn-light']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
