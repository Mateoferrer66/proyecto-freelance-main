<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Factura $model */

?>
<div class="factura-send-email-form">
    <?php if (empty($model->cli->cli_email)): ?>
        <div class="alert alert-danger">
            El cliente no tiene una dirección de correo electrónico configurada.
        </div>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    <?php else: ?>
        <p>Se enviará la factura #<?= Html::encode($model->fac_numero) ?> al siguiente correo electrónico:</p>
        <p><strong><?= Html::encode($model->cli->cli_email) ?></strong></p>
        <p>¿Desea continuar?</p>

        <?php $form = ActiveForm::begin([
            'action' => ['factura/do-send-email', 'fac_id' => $model->fac_id],
            'method' => 'post',
        ]); ?>

        <div class="form-group">
            <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>

        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>
