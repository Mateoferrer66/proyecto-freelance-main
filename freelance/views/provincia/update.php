<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Provincia $model */

$this->title = 'Update Provincia: ' . $model->prv_id;
$this->params['breadcrumbs'][] = ['label' => 'Provincias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->prv_id, 'url' => ['view', 'prv_id' => $model->prv_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="provincia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
