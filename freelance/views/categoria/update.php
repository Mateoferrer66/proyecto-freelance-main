<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Categoria $model */

$this->title = 'Update Categoria: ' . $model->cat_id;
$this->params['breadcrumbs'][] = ['label' => 'Categorias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cat_id, 'url' => ['view', 'cat_id' => $model->cat_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="categoria-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
