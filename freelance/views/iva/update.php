<?php

/** @var yii\web\View $this */
/** @var app\models\Iva $model */

$this->title = 'Actualizar Iva: ' . $model->iva_id;
?>
<div class="iva-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>