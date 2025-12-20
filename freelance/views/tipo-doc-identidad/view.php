<?php

use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\TipoDocIdentidad $model */

?>
<div class="tipo-doc-identidad-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tdo_codigo',
            'tdo_nombre',
        ],
    ]) ?>

</div>