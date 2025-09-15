<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Iva $model */

$this->title = 'Update Iva: ' . $model->iva_id;
$this->params['breadcrumbs'][] = ['label' => 'Iva', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->iva_id, 'url' => ['view', 'iva_id' => $model->iva_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="iva-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
