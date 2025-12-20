<?php

use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ConceptoLiquidacion $model */
?>
<div class="concepto-liquidacion-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'col_id',
            'col_codigo',
            'col_nombre',
            'col_clasificacion',
            'col_tipo',
            'col_porcentaje',
            'col_valor',
        ],
    ]) ?>

</div>