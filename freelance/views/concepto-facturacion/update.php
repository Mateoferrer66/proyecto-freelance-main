<?php

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacion $model */

$this->title = 'Actualizar Concepto: ' . $model->cof_nombre;
?>
<div class="concepto-facturacion-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>