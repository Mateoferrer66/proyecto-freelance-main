<?php

/** @var yii\web\View $this */
/** @var app\models\Iva $model */

$this->title = 'Crear Iva';
?>
<div class="iva-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>