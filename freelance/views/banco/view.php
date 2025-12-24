<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Banco $model */
?>
<div class="banco-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ban_id',
            'ban_nombre',
            'ban_numcuenta',
            [
                'attribute' => 'ban_eliminado',
                'value' => $model->ban_eliminado ? 'Sí' : 'No',
            ],
        ],
    ]) ?>

</div>
