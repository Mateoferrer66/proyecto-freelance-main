<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Usuario $model */

$this->title = 'Detalle de Usuario: ' . $model->usu_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="usuario-view">

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4>VISUALIZAR USUARIO</h4>
            <p><small>DATOS DEL USUARIO</small></p>
        </div>
        <div>
            <?= Html::a('Modificar', ['update', 'usu_id' => $model->usu_id], ['class' => 'btn btn-success px-5 radius-30']) ?>
            <?= Html::a('Eliminar', ['delete', 'usu_id' => $model->usu_id], [
                'class' => 'btn btn-success px-5 radius-30',
                'data' => [
                    'confirm' => '¿Está seguro de que desea eliminar este usuario?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <hr>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'usu_nombre',
            'usu_apellido',
            'usu_email:email',
            'usu_login',
            'usu_estado',
            'usu_fecbloqueo',
        ],
    ]) ?>

</div>
