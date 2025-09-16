<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TipoDocIdentidad $model */

$this->title = 'Update Tipo Doc Identidad: ' . $model->tdo_id;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Doc Identidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tdo_id, 'url' => ['view', 'tdo_id' => $model->tdo_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipo-doc-identidad-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
