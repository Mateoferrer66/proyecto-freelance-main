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

    <?php
        //Funciones para mostrar alert/confirm con diseño
        $this->registerJs(<<<JS
            yii.confirm = function(message, ok, cancel) {
                showConfirm(message, ok, cancel);
            };
        JS, \yii\web\View::POS_END);

        $this->registerJs(<<<JS
            window.showToast = function showToast(message, type = 'success') {
                const config = {
                    success: { bg: '#28a745', icon: '✓' },
                    error:   { bg: '#dc3545', icon: '✕' },
                    warning: { bg: '#fd7e14', icon: '⚠' },
                    info:    { bg: '#17a2b8', icon: 'ℹ' }
                };
                const { bg, icon } = config[type] || config.success;

                const toast = \$(`<div style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: #1e1e1e;
                    color: #fff;
                    padding: 14px 18px;
                    border-radius: 10px;
                    z-index: 9999;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.35);
                    font-size: 14px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    min-width: 260px;
                    max-width: 380px;
                    border-left: 4px solid \${bg};
                    opacity: 0;
                    transform: translateX(40px);
                    transition: all 0.3s ease;
                ">
                    <span style="
                        background: \${bg};
                        color: white;
                        width: 26px;
                        height: 26px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 13px;
                        flex-shrink: 0;
                    ">\${icon}</span>
                    <span style="flex: 1; line-height: 1.4;">\${message}</span>
                    <span class="toast-close" style="
                        cursor: pointer;
                        color: #aaa;
                        font-size: 16px;
                        line-height: 1;
                        padding: 0 2px;
                    ">×</span>
                </div>`);

                \$('body').append(toast);

                // Animación de entrada
                setTimeout(() => {
                    toast.css({ opacity: 1, transform: 'translateX(0)' });
                }, 10);

                // Barra de progreso
                const bar = \$(`<div style="
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    height: 3px;
                    width: 100%;
                    background: \${bg};
                    border-radius: 0 0 10px 10px;
                    opacity: 0.5;
                    transition: width 3s linear;
                "></div>`);
                toast.css('position', 'fixed').append(bar);
                setTimeout(() => bar.css('width', '0%'), 50);

                // Cerrar al hacer click en la X
                toast.find('.toast-close').on('click', () => {
                    toast.css({ opacity: 0, transform: 'translateX(40px)' });
                    setTimeout(() => toast.remove(), 300);
                });

                // Auto cerrar
                setTimeout(() => {
                    toast.css({ opacity: 0, transform: 'translateX(40px)' });
                    setTimeout(() => toast.remove(), 300);
                }, 3000);

                // Apilar toasts si hay varios
                const offset = (\$('.toast-notification').length * 70);
                toast.addClass('toast-notification').css('top', (20 + offset) + 'px');
            }

            // Reemplaza el alert nativo
            window.alert = function(message) {
                showToast(message, 'info');
            };

            // Confirmación visual
            window.showConfirm = function showConfirm(message, onConfirm, onCancel = null) {
                const overlay = \$(`<div style="
                    position: fixed;
                    inset: 0;
                    background: rgba(0,0,0,0.6);
                    z-index: 9998;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    opacity: 0;
                    transition: opacity 0.25s ease;
                ">
                    <div style="
                        background: #1e1e1e;
                        color: #fff;
                        border-radius: 12px;
                        padding: 32px;
                        max-width: 420px;
                        width: 90%;
                        box-shadow: 0 8px 32px rgba(0,0,0,0.5);
                        text-align: center;
                        transform: scale(0.9);
                        transition: transform 0.25s ease;
                    ">
                        <div style="
                            width: 52px;
                            height: 52px;
                            background: #fd7e14;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 24px;
                            margin: 0 auto 16px;
                        ">⚠</div>
                        <p style="margin: 0 0 24px; font-size: 15px; line-height: 1.6; color: #ddd;">
                            \${message}
                        </p>
                        <div style="display: flex; gap: 12px; justify-content: center;">
                            <button id="confirmYes" style="
                                background: #28a745;
                                color: white;
                                border: none;
                                padding: 10px 28px;
                                border-radius: 8px;
                                cursor: pointer;
                                font-size: 14px;
                                transition: opacity 0.2s;
                            ">Confirmar</button>
                            <button id="confirmNo" style="
                                background: #444;
                                color: white;
                                border: none;
                                padding: 10px 28px;
                                border-radius: 8px;
                                cursor: pointer;
                                font-size: 14px;
                                transition: opacity 0.2s;
                            ">Cancelar</button>
                        </div>
                    </div>
                </div>`);

                \$('body').append(overlay);

                // Animación entrada
                setTimeout(() => {
                    overlay.css('opacity', 1);
                    overlay.find('div').first().css('transform', 'scale(1)');
                }, 10);

                const close = () => {
                    overlay.css('opacity', 0);
                    setTimeout(() => overlay.remove(), 250);
                };

                overlay.find('#confirmYes').on('click', function() {
                    close();
                    if (typeof onConfirm === 'function') onConfirm();
                });

                overlay.find('#confirmNo').on('click', function() {
                    close();
                    if (typeof onCancel === 'function') onCancel();
                });

                // Cerrar al hacer click fuera
                overlay.on('click', function(e) {
                    if (\$(e.target).is(overlay)) {
                        close();
                        if (typeof onCancel === 'function') onCancel();
                    }
                });
            }
            JS, \yii\web\View::POS_READY
        );
    ?>

</body>

</html>
<?php $this->endPage() ?>