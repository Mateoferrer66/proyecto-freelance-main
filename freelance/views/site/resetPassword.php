<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Restablecer contraseña';
?>

<div class="section-authentication-signin loginFlex">
    <div class="logoLogin">
        <img src="<?= Url::to('@web/assets-custom/images/logotipo.svg') ?>" alt="smartsy" />
        <div id="animate-area" style="background-image: url(<?= Url::to('@web/assets-custom/images/bg-themes/bcloud.jpg') ?>);"></div>
    </div>
    <div class="card card-login">
        <div class="text-center">
            <h3 class=""><?= Html::encode($this->title) ?></h3>
            <p>Panel de administración</p>
        </div>
        <div class="form-body">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'class' => 'form-control', 'placeholder' => 'Nueva contraseña']) ?>

            <div class="col-12 mt-3">
                <div class="d-grid">
                    <?= Html::submitButton('Cambiar contraseña', ['class' => 'btn btn-light']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
