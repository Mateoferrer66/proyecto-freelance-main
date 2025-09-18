<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Consecutivo $model */

$this->title = 'Update Consecutivo: ' . $model->con_serie;
$this->params['breadcrumbs'][] = ['label' => 'Consecutivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->con_serie, 'url' => ['view', 'con_serie' => $model->con_serie, 'con_consecutivo' => $model->con_consecutivo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="consecutivo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
