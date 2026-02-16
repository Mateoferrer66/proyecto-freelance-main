<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Portafolio $model */

$this->title = $model->por_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Portafolio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerCss("
    .image-container {
        display: inline-block;
        margin: 10px;
    }
    .image-container img {
        max-width: 200px;
        max-height: 200px;
        border: 2px solid #ddd;
        border-radius: 5px;
    }
");
?>
<div class="page-content">

    <h6 class="mb-0 text-uppercase"><?= Html::encode($this->title) ?></h6>
    <hr/>

    <div class="card">
        <div class="card-body">
            <p>
                <?= Html::a('Editar', ['update', 'por_id' => $model->por_id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Eliminar', ['delete', 'por_id' => $model->por_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => '¿Está seguro de que desea eliminar este proyecto?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a('Volver', ['index'], ['class' => 'btn btn-secondary']) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'por_id',
                    [
                        'attribute' => 'soc_id',
                        'label' => 'Socio',
                        'value' => $model->soc ? $model->soc->soc_codigo . ' - ' . $model->soc->soc_nombre : '',
                    ],
                    'por_titulo',
                    'por_descripcion:ntext',
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:d-m-Y H:i:s'],
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format' => ['date', 'php:d-m-Y H:i:s'],
                    ],
                ],
            ]) ?>

            <?php if (!empty($model->getImageArray())): ?>
            <div class="mt-4">
                <h5>Imágenes del Proyecto</h5>
                <div class="images-gallery">
                    <?php foreach ($model->getImageArray() as $img): ?>
                        <div class="image-container">
                            <a href="/<?= Html::encode($img) ?>" target="_blank">
                                <img src="/<?= Html::encode($img) ?>" alt="Imagen del proyecto">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
