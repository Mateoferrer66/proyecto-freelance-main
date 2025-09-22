<?php

/** @var yii\web\View $this */
/** @var app\models\ConceptoLiquidacion $model */

$this->title = 'Actualizar Concepto: ' . $model->col_nombre;
?>
<div class="concepto-liquidacion-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>