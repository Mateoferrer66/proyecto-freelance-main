<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Banco $model */

$this->title = 'Update Banco: ' . $model->ban_id;
$this->params['breadcrumbs'][] = ['label' => 'Bancos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ban_id, 'url' => ['view', 'ban_id' => $model->ban_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="banco-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
