<?php

use app\models\TipoDocIdentidad;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;


/** @var yii\web\View $this */
/** @var app\models\TipoDocIdentidadSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestión de Tipos de Documento de Identidad';
$this->params['breadcrumbs'] = [];
?>
<div class="tipo-doc-identidad-index">

    <?= $this->render('@app/views/layouts/_orangemenu') ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e2f;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #2a2a3b;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            text-transform: uppercase;
            color: #ffa500;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-bar input {
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            width: 300px;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #ffa500;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            text-transform: uppercase;
        }

        .buttons button:hover {
            background-color: #ff8c00;
        }
    </style>

    <div class="mb-3" style="margin-top:20px;">
        <?= Html::a('<i class="bx bx-plus-medical"></i> Crear Tipo Doc Identidad', ['create'], [
            'class' => 'btn btn-success px-4 radius-30',
            'title' => 'Agregar nuevo tipo de documento',
        ]) ?>
    </div>

    <div class="col d-flex justify-content-between align-items-start">
        <h6 class="mb-0 text-uppercase">
            Documentos de Identidad <span class="badge bg-warning text-dark"><?= $dataProvider->getTotalCount() ?></span>
        </h6>
    </div>

    <div class="container">
        <div class="header">
            <h1>Gestión de Tipos de Documento de Identidad</h1>
          <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'search-bar'],
            ]); ?>

            <?= $form->field($searchModel, 'tdo_nombre', ['template' => '{input}'])
                ->textInput(['placeholder' => 'Buscar identidad...']) ?>

            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="buttons">
            <?= Html::a('Excel', ['tipo-doc-identidad/export-excel'], [
                'target' => '_blank',
                'style' => 'padding:10px 20px;border:none;border-radius:5px;background-color:#ffa500;color:#fff;cursor:pointer;font-size:16px;text-transform:uppercase;text-decoration:none;'
            ]) ?>
            <?= Html::a('PDF', ['tipo-doc-identidad/export-pdf'], [
                'target' => '_blank',
                'style' => 'padding:10px 20px;border:none;border-radius:5px;background-color:#ffa500;color:#fff;cursor:pointer;font-size:16px;text-transform:uppercase;text-decoration:none;'
            ]) ?>
            <?= Html::a('Print', ['tipo-doc-identidad/print'], [
                'target' => '_blank',
                'style' => 'padding:10px 20px;border:none;border-radius:5px;background-color:#ffa500;color:#fff;cursor:pointer;font-size:16px;text-transform:uppercase;text-decoration:none;'
            ]) ?>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'summary' => false,
            'columns' => [
                [
                    'attribute' => 'tdo_id',
                    'label' => 'Código',
                ],
                [
                    'attribute' => 'tdo_nombre',
                    'label' => 'Nombre',
                ],
                [
                    'class' => ActionColumn::class,
                    'header' => 'Acciones',
                    'urlCreator' => function ($action, TipoDocIdentidad $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'tdo_id' => $model->tdo_id]);
                    }
                ],
            ],
        ]); ?>
    </div>
</div>