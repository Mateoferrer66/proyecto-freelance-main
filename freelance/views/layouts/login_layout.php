<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\PanelAsset;
use yii\helpers\Html;
use yii\helpers\Url;

PanelAsset::register($this);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!--favicon-->
    <link rel="icon" href="<?= Url::to('@web/assets-custom/images/favicon-32x32.png') ?>" type="image/png" />
</head>

<body class="bg-theme bg-theme3">
<?php $this->beginBody() ?>

<!--wrapper-->
<div class="wrapper" style="display: block;">
    <?= $content ?>
</div>
<!--end wrapper-->

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
