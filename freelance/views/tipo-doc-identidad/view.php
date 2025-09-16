<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\TipoDocIdentidad $model */

$this->title = $model->tdo_id;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Doc Identidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tipo-doc-identidad-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'tdo_id' => $model->tdo_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'tdo_id' => $model->tdo_id], [
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
            'tdo_id',
            'tdo_nombre',
            'tdo_eliminado',
        ],
    ]) ?>

</div>
