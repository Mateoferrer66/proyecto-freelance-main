<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\PanelAsset;
use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

PanelAsset::register($this);
AppAsset::register($this);

$this->registerJs("$('#menu').metisMenu(); // Inicializa el menú desplegable");
$this->registerJs("var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle')); var dropdownList = dropdownElementList.map(function (dropdownToggleEl) { return new bootstrap.Dropdown(dropdownToggleEl) });");
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        .alert {
            color: #ffffff !important;
            font-weight: 500;
        }

        .alert-success {
            background-color: #1e3a2f;
            border-color: #2ecc71;
            color: #d4f8e8 !important;
        }

        .alert-danger {
            background-color: #3a1e1e;
            border-color: #e74c3c;
            color: #ffd6d6 !important;
        }

        .alert-warning {
            background-color: #3a331e;
            border-color: #f1c40f;
            color: #fff3cd !important;
        }
        /* Estilos globales para dropdowns en modo oscuro */
    body.dark-theme select.form-select, 
    body.dark-theme select.form-control,
    body.dark-theme .form-select,
    body.dark-theme .form-control {
        background-color: #2b303b !important;
        color: #fff !important;
        border-color: #444 !important;
    }
    
    body.dark-theme select option {
        background-color: #2b303b !important;
        color: #fff !important;
    }

    /* Select2 en modo oscuro */
    body.dark-theme .select2-container--default .select2-selection--single {
        background-color: #2b303b !important;
        border-color: #444 !important;
        color: #fff !important;
    }
    body.dark-theme .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #fff !important;
    }
    body.dark-theme .select2-dropdown {
        background-color: #2b303b !important;
        color: #fff !important;
        border-color: #444 !important;
    }
    body.dark-theme .select2-results__option {
        background-color: #2b303b !important;
        color: #fff !important;
    }
    body.dark-theme .select2-results__option--highlighted[aria-selected],
    body.dark-theme .select2-results__option--highlighted {
        background-color: #3b4252 !important;
        color: #fff !important;
    }
    body.dark-theme .select2-search--dropdown .select2-search__field {
        background-color: #3b4252 !important;
        color: #fff !important;
        border-color: #555 !important;
    }
    /* Global Dark Mode fixes for Selects, Inputs and Dropdowns */
    body.dark-theme select,
    body.dark-theme select.form-select,
    body.dark-theme select.form-control,
    body.dark-theme input:disabled,
    body.dark-theme input[readonly],
    body.bg-theme3 select,
    body.bg-theme3 select.form-select,
    body.bg-theme3 select.form-control,
    body.bg-theme3 input:disabled,
    body.bg-theme3 input[readonly] {
        background-color: #2b303b !important;
        color: #fff !important;
        border-color: #555 !important;
    }

    body.dark-theme select,
    body.dark-theme select.form-select,
    body.bg-theme3 select,
    body.bg-theme3 select.form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right .75rem center !important;
        background-size: 16px 12px !important;
        appearance: none !important;
    }

    /* Force background for options to avoid white background in some browsers */
    body.dark-theme select option,
    body.bg-theme3 select option {
        background-color: #2b303b !important;
        color: #fff !important;
    }

    /* Target specifically Select2 and other JS dropdowns */
    .select2-container--bootstrap-5 .select2-selection,
    .select2-container--default .select2-selection--single,
    .select2-dropdown,
    .select2-results__option {
        background-color: #2b303b !important;
        color: #fff !important;
        border-color: #555 !important;
    }

    /* Fix labels color in some sections if needed */
    body.dark-theme label, body.bg-theme3 label {
        color: #fff !important;
    }

    /* Checkboxes and Radios */
    .form-check-input {
        background-color: #3b4252 !important;
        border-color: #555 !important;
    }
    .form-check-input:checked {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
    }
    .form-check-label {
        color: #fff !important;
    }
    /* Forcing dark background for select options across all browsers */
    body.dark-theme select option,
    body.dark-theme select.form-select option,
    body.dark-theme select.form-control option,
    body.bg-theme3 select option,
    body.bg-theme3 select.form-select option,
    body.bg-theme3 select.form-control option {
        background-color: #2b303b !important;
        color: #fff !important;
    }

    /* Target selects directly in case classes are missing */
    body.dark-theme select, 
    body.bg-theme3 select {
        background-color: #2b303b !important;
        color: #fff !important;
    }

    /* Checkboxes and Radios in dark mode */
    .form-check-input {
        background-color: #3b4252 !important;
        border-color: #555 !important;
    }
    .form-check-input:checked {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
    }
    .form-check-label {
        color: #fff !important;
    }

    /* Improving visibility of Select2 dropdown */
    .select2-container--default .select2-results > .select2-results__options {
        background-color: #2b303b !important;
    }
    .select2-container--default .select2-selection--single {
        background-color: #2b303b !important;
        border: 1px solid #444 !important;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        color: #fff !important;
    }
</style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= Url::to('@web/assets-custom/images/favicon-32x32.png') ?>" type="image/png" />
</head>

<body class="bg-theme bg-theme3">
    <div class="wrapper">

        <?php $this->beginBody() ?>
        <?= $this->render('_sidebar') ?>
        <header id="topbar">
            <?= $this->render('_topbar') ?>
        </header>
        
        <div class="page-wrapper">

            <?php Pjax::begin([
                'id' => 'pjax-container',
                'enablePushState' => true,
                'timeout' => 5000,
                'scrollTo' => 0,
                'linkSelector' => '#menu a:not([data-method]):not([data-pjax="0"]), a[data-pjax]',
            ]); ?>

            

            <main id="main" class="flex-shrink-0" role="main">


                <?php if (!empty($this->params['breadcrumbs'])): ?>
                    <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
                <?php endif ?>
                <?= Alert::widget() ?>
                <?= $content ?>

            </main>
            
            <?php Pjax::end(); ?>
        </div>
        <?php

        Modal::begin([
            'id' => 'action-modal',
            'title' => '<h4 class="modal-title"></h4>',
            'size' => 'modal-lg',
            'footer' => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>',
        ]);
        echo "<div id='modal-content'></div>";
        Modal::end();
        ?>
        <?php $this->endBody() ?>

    </div>

</body>

</html>
<?php $this->endPage() ?>