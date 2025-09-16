<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TipoDocIdentidad $model */

$this->title = 'Create Tipo Doc Identidad';
$this->params['breadcrumbs'][] = ['label' => 'Tipo Doc Identidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-doc-identidad-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
