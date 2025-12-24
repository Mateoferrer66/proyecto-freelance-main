<?php

use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Iva $model */
?>
<div class="iva-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'iva_id',
            'iva_porcentaje',
            'iva_concepto',
        ],
    ]) ?>

</div>