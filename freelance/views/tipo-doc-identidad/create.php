<?php

/** @var yii\web\View $this */
/** @var app\models\TipoDocIdentidad $model */

$this->title = 'Crear Tipo de Documento de Identidad';
?>
<div class="tipo-doc-identidad-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>