<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ConceptoLiquidacion $model */

$this->title = 'Create Concepto Liquidacion';
$this->params['breadcrumbs'][] = ['label' => 'Concepto Liquidacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="concepto-liquidacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
