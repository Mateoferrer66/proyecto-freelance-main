<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Presupuesto $model */

$this->title = $model->pre_id;
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="presupuesto-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'pre_id' => $model->pre_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'pre_id' => $model->pre_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pre_id',
            'pre_numero',
            'pre_logo',
            'pre_fecha',
            'pre_language',
            'cli_id',
            'soc_id',
            'fdp_id',
            'pre_subtotal',
            'pre_iva',
            'pre_gastos_suplidos',
            'pre_total',
            'pre_observaciones:ntext',
            'pre_eliminado',
        ],
    ]) ?>

</div>
