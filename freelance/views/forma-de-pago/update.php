<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\FormaDePago $model */

$this->title = 'Update Forma De Pago: ' . $model->fdp_id;
$this->params['breadcrumbs'][] = ['label' => 'Forma De Pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fdp_id, 'url' => ['view', 'fdp_id' => $model->fdp_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="forma-de-pago-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
