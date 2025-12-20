<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Empresa $model */

$this->title = $model->emp_razon_social;
$this->params['breadcrumbs'][] = ['label' => 'Empresas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="page-content">

    <h6 class="mb-0 text-uppercase">Detalles de la Empresa</h6>
    <hr />
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body p-5">
                    <?= DetailView::widget([
                        'model' => $model,
                        'class' => 'table table-striped table-bordered',
                        'attributes' => [
                            'emp_id',
                            'emp_razon_social',
                            'tdo_id',
                            'emp_numdocide',
                            'emp_direccion',
                            'emp_codpostal',
                            'emp_poblacion',
                            'emp_telefono',
                            'emp_fax',
                            'emp_email:email',
                            'emp_regimen_segs',
                            'emp_ccc_segs',
                            'emp_tipo_segs',
                            'emp_razons_segs',
                            'emp_participaciones',
                        ],
                    ]) ?>
                    <hr />
                    <p>
                        <?= Html::a('Update', ['update', 'emp_id' => $model->emp_id], ['class' => 'btn btn-success px-5 radius-30']) ?>
                        <?= Html::a('Delete', ['delete', 'emp_id' => $model->emp_id], [
                            'class' => 'btn btn-success px-5 radius-30',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
