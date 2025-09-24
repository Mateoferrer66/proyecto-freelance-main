<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Cliente $model */

$this->title = $model->cli_id;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cliente-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'cli_id' => $model->cli_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'cli_id' => $model->cli_id], [
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
            'cli_id',
            'cli_numero',
            'cli_nombre',
            'cli_persona_contacto',
            'tdo_id',
            'cli_docinipais',
            'cli_numdocide',
            'cli_feccaddoc',
            'cli_tel1',
            'cli_tel2',
            'cli_direccion',
            'pai_id',
            'prv_id',
            'cli_poblacion',
            'cli_codpostal',
            'cli_email:email',
            'cli_cuenta_contable',
            'iva_id',
            'fdp_id',
            'soc_id',
            'cli_observaciones:ntext',
            'cli_estado',
            'cli_exportado',
            'cli_eliminado',
        ],
    ]) ?>

</div>
