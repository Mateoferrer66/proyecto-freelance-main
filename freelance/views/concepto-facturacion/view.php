<?php

use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacion $model */
?>
<div class="concepto-facturacion-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cof_id',
            'cof_codigo',
            'cof_nombre',
            [
                'attribute' => 'iva_id',
                'value' => $model->iva ? $model->iva->iva_concepto : 'N/A',
            ],
            'cof_clasificacion',
        ],
    ]) ?>

</div>