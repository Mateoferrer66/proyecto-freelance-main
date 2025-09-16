<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\FormaDePago $model */

$this->title = $model->fdp_id;
$this->params['breadcrumbs'][] = ['label' => 'Forma De Pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="forma-de-pago-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'fdp_id' => $model->fdp_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'fdp_id' => $model->fdp_id], [
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
            'fdp_id',
            'fdp_nombre',
            'fdp_eliminada',
        ],
    ]) ?>

</div>
