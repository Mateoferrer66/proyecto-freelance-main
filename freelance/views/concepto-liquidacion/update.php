<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ConceptoLiquidacion $model */

$this->title = 'Update Concepto Liquidacion: ' . $model->col_id;
$this->params['breadcrumbs'][] = ['label' => 'Concepto Liquidacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->col_id, 'url' => ['view', 'col_id' => $model->col_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="concepto-liquidacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
