<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Presupuesto $model */

$this->title = 'Update Presupuesto: ' . $model->pre_id;
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pre_id, 'url' => ['view', 'pre_id' => $model->pre_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="presupuesto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
