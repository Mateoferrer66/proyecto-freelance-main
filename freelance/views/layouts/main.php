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