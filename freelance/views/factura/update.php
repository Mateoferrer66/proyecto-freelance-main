<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Factura $model */

$this->title = 'Update Factura: ' . $model->fac_id;
$this->params['breadcrumbs'][] = ['label' => 'Facturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fac_id, 'url' => ['view', 'fac_id' => $model->fac_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="factura-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
