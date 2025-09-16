<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacion $model */

$this->title = $model->cof_id;
$this->params['breadcrumbs'][] = ['label' => 'Concepto Facturacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="concepto-facturacion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'cof_id' => $model->cof_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'cof_id' => $model->cof_id], [
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
            'cof_id',
            'iva_id',
            'cof_codigo',
            'cof_nombre',
            'cof_clasificacion',
            'cof_eliminado',
        ],
    ]) ?>

</div>
