<?php

/** @var yii\web\View $this */
/** @var app\models\TipoDocIdentidad $model */

$this->title = 'Actualizar Tipo de Documento de Identidad: ' . $model->tdo_nombre;
?>
<div class="tipo-doc-identidad-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>