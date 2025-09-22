<?php

/** @var yii\web\View $this */
/** @var app\models\ConceptoLiquidacion $model */

$this->title = 'Crear Concepto de Liquidación';
?>
<div class="concepto-liquidacion-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>