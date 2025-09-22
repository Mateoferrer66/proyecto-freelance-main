<?php

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacion $model */

$this->title = 'Crear Concepto de Facturación';
?>
<div class="concepto-facturacion-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>