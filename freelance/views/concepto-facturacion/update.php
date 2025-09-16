<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacion $model */

$this->title = 'Update Concepto Facturacion: ' . $model->cof_id;
$this->params['breadcrumbs'][] = ['label' => 'Concepto Facturacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cof_id, 'url' => ['view', 'cof_id' => $model->cof_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="concepto-facturacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
