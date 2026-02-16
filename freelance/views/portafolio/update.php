<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Portafolio $model */
/** @var array $socios */

$this->title = 'EDITAR PROYECTO: ' . $model->por_titulo;
$this->params['breadcrumbs'][] = ['label' => 'Portafolio', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Editar';

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

    // Delete image handler
    $('.delete-image-btn').on('click', function(e){
        e.preventDefault();
        if (confirm('¿Está seguro de que desea eliminar esta imagen?')) {
            var url = $(this).data('url');
            var imageContainer = $(this).closest('.image-container');
            
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        imageContainer.fadeOut(300, function(){ $(this).remove(); });
                    } else {
                        alert('Error al eliminar la imagen: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error al procesar la solicitud.');
                }
            });
        }
    });
});
JS;

$js = str_replace('$urlListadoSocios', $urlListadoSocios, $js);
$this->registerJs($js);

$this->registerCss("
    .image-container {
        position: relative;
        display: inline-block;
        margin: 10px;
    }
    .image-container img {
        max-width: 150px;
        max-height: 150px;
        border: 2px solid #ddd;
        border-radius: 5px;
    }
    .delete-image-btn {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
    }
    .delete-image-btn:hover {
        background: #c82333;
    }
");

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

                    <?php if (!empty($model->getImageArray())): ?>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label>Imágenes actuales</label>
                            <div class="images-gallery">
                                <?php foreach ($model->getImageArray() as $img): ?>
                                    <div class="image-container">
                                        <img src="/<?= Html::encode($img) ?>" alt="Imagen del proyecto">
                                        <button type="button" class="delete-image-btn" 
                                                data-url="<?= Url::to(['delete-image', 'por_id' => $model->por_id, 'image' => $img]) ?>"
                                                title="Eliminar imagen">×</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-12 mb-3">
                            <?= $form->field($model, 'imageFiles[]', [
                                'template' => "<label>Agregar más imágenes</label>\n{input}\n{hint}\n{error}"
                            ])->fileInput(['multiple' => true, 'accept' => 'image/*', 'class' => 'form-control']) ?>
                            <small class="text-muted">Puede seleccionar múltiples imágenes (PNG, JPG, JPEG, GIF, WEBP)</small>
                        </div>
                    </div>

                    <hr>

                    <div class="col-12">
                        <?= Html::submitButton('Actualizar Proyecto', ['class' => 'btn btn-success px-5 radius-30']) ?>
                        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-secondary px-5 radius-30']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
