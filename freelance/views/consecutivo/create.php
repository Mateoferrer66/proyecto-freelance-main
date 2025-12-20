<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Consecutivo $model */

$this->title = 'Create Consecutivo';
$this->params['breadcrumbs'][] = ['label' => 'Consecutivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consecutivo-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
