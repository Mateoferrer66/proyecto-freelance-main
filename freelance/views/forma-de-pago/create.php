<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\FormaDePago $model */

$this->title = 'Create Forma De Pago';
$this->params['breadcrumbs'][] = ['label' => 'Forma De Pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forma-de-pago-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
