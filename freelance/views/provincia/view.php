<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Provincia $model */

$this->title = $model->prv_id;
$this->params['breadcrumbs'][] = ['label' => 'Provincias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="provincia-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'prv_id' => $model->prv_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'prv_id' => $model->prv_id], [
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
            'prv_id',
            'pai_id',
            'prv_nombre',
            'prv_eliminada',
        ],
    ]) ?>

</div>
