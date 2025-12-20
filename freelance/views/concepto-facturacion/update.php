<?php
use yii\helpers\Html;
use app\models\ConceptoFacturacion;

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacion $model */

?>
<div class="concepto-facturacion-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>