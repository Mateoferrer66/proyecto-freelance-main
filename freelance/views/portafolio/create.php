<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Portafolio $model */
/** @var array $socios */

$this->title = 'CREAR PROYECTO';
$this->params['breadcrumbs'][] = ['label' => 'Portafolio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// CSS y JS para jQuery UI (Autocomplete)
$this->registerCssFile("https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("https://code.jquery.com/ui/1.13.2/jquery-ui.js", ['depends' => [\yii\web\JqueryAsset::class]]);

// URL para AJAX
$urlListadoSocios = Url::to(['portafolio/listado-socios']);

$js = <<<'JS'
$(function(){
    // Setup Socio Autocomplete
    $("#search-socio").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: '$urlListadoSocios',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 0,
        select: function(event, ui) {
            $("#portafolio-soc_id").val(ui.item.value);
            $(this).val(ui.item.label);
            return false;
        }
    }).focus(function(){ 
        $(this).autocomplete("search");
    });
});
JS;

$js = str_replace('$urlListadoSocios', $urlListadoSocios, $js);
$this->registerJs($js);

?>

<div class="page-content">
    <h6 class="mb-0 text-uppercase"><?= Html::encode($this->title) ?> <dl>* Datos obligatorios</dl></h6>
    <hr/>
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body p-5">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form',
                            'id' => 'portafolioForm',
                            'enctype' => 'multipart/form-data',
                        ]
                    ]); ?>

                    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

                    <div class="row mb-3">
                        <div class="col-12 col-md-6 mb-3">
                            <label>Socio *</label>
                            <?php 
                            // Hidden input for ID
                            echo $form->field($model, 'soc_id')->hiddenInput()->label(false);
                            
                            // Visual input for search
                            $socioName = '';
                            if ($model->soc_id && isset($socios[$model->soc_id])) {
                                $socioName = $socios[$model->soc_id];
                            }
                            ?>
                            <input type="text" id="search-socio" class="form-control mb-3" placeholder="Buscar socio..." value="<?= $socioName ?>" required>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <?= $form->field($model, 'por_titulo', [
                                'template' => "<label>Título del Proyecto *</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control', 'required' => true]
                            ])->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 mb-3">
                            <?= $form->field($model, 'por_descripcion', [
                                'template' => "<label>Descripción del Proyecto *</label>\n{input}\n{hint}\n{error}",
                            ])->textarea(['rows' => 6, 'class' => 'form-control', 'required' => true]) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 mb-3">
                            <?= $form->field($model, 'imageFiles[]', [
                                'template' => "<label>Imágenes del Proyecto</label>\n{input}\n{hint}\n{error}"
                            ])->fileInput(['multiple' => true, 'accept' => 'image/*', 'class' => 'form-control']) ?>
                            <small class="text-muted">Puede seleccionar múltiples imágenes (PNG, JPG, JPEG, GIF, WEBP)</small>
                        </div>
                    </div>

                    <hr>

                    <div class="col-12">
                        <?= Html::submitButton('Crear Proyecto', ['class' => 'btn btn-success px-5 radius-30']) ?>
                        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-secondary px-5 radius-30']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
