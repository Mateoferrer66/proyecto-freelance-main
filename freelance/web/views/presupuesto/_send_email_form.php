<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Presupuesto $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="presupuesto-send-email-form">
    <?php $form = ActiveForm::begin(['id' => 'send-email-form']); ?>

    <div class="mb-3">
        <label for="email-to" class="form-label">Enviar presupuesto a:</label>
        <?= Html::textInput('email_to', $model->cli->cli_email, ['class' => 'form-control', 'id' => 'email-to']) ?>
    </div>

    <div class="mb-3">
        <label for="email-subject" class="form-label">Asunto:</label>
        <?= Html::textInput('email_subject', 'Presupuesto ' . $model->pre_numero, ['class' => 'form-control', 'id' => 'email-subject']) ?>
    </div>

    <div class="mb-3">
        <label for="email-body" class="form-label">Mensaje:</label>
        <?= Html::textarea('email_body', 'Adjunto encontrará el presupuesto ' . $model->pre_numero . '.', ['class' => 'form-control', 'rows' => 5, 'id' => 'email-body']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
