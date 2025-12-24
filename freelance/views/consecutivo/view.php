<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Consecutivo $model */

$this->title = $model->con_serie;
$this->params['breadcrumbs'][] = ['label' => 'Consecutivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="consecutivo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'con_serie' => $model->con_serie, 'con_consecutivo' => $model->con_consecutivo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'con_serie' => $model->con_serie, 'con_consecutivo' => $model->con_consecutivo], [
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
            'con_serie',
            'con_consecutivo',
        ],
    ]) ?>

</div>
