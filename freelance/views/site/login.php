<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Ingreso';

// JS for password visibility
$this->registerJs("
$(document).ready(function () {
    $('#show_hide_password a').on('click', function (event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr('type') == 'text') {
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass('bx-hide');
            $('#show_hide_password i').removeClass('bx-show');
        } else if ($('#show_hide_password input').attr('type') == 'password') {
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass('bx-hide');
            $('#show_hide_password i').addClass('bx-show');
        }
    });
});
");
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
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'row g-3'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'form-label visually-hidden'], // Hide labels as placeholders are used
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback'],
                ],
            ]); ?>

            <div class="col-12">
                <?= $form->field($model, 'usu_login', [
                    'inputOptions' => [
                        'class' => 'form-control',
                        'placeholder' => 'Usuario',
                        'autofocus' => true
                    ]
                ])->label('Usuario') ?>
            </div>

            <div class="col-12">
                <?= $form->field($model, 'usu_password', [
                    'template' => '{label}<div class="input-group" id="show_hide_password">{input}<a href="javascript:;" class="input-group-text bg-transparent"><i class=\'bx bx-hide\'></i></a></div>{error}',
                    'inputOptions' => [
                        'class' => 'form-control border-end-0',
                        'placeholder' => 'Contraseña'
                    ]
                ])->passwordInput()->label('Contraseña') ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => '<div class="form-check form-switch">{input}{label}</div>',
                    'class' => 'form-check-input'
                ]) ?>
            </div>

            <div class="col-md-6 text-end">
                <a href="#">Olvidó su contraseña ?</a>
            </div>

            <div class="col-12">
                <div class="d-grid">
                    <?= Html::submitButton('<i class="bx bxs-lock-open"></i>Ingresar', ['class' => 'btn btn-light', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
