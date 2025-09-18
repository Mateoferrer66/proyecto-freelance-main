<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Empresa $model */

$this->title = $model->emp_id;
$this->params['breadcrumbs'][] = ['label' => 'Empresas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="empresa-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'emp_id' => $model->emp_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'emp_id' => $model->emp_id], [
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
            'emp_id',
            'emp_razon_social',
            'tdo_id',
            'emp_numdocide',
            'emp_direccion',
            'emp_codpostal',
            'emp_poblacion',
            'emp_telefono',
            'emp_fax',
            'emp_email:email',
            'emp_regimen_segs',
            'emp_ccc_segs',
            'emp_tipo_segs',
            'emp_razons_segs',
            'emp_participaciones',
        ],
    ]) ?>

</div>
