<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ConceptoFacturacion $model */

$this->title = 'Create Concepto Facturacion';
$this->params['breadcrumbs'][] = ['label' => 'Concepto Facturacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="concepto-facturacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
