<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';

(new yii\web\Application($config));

$suplidos = \app\models\ConceptoFacturacion::find()->where(['like', 'cof_nombre', 'suplido'])->asArray()->all();
echo "SUPLIDOS_JSON:" . json_encode($suplidos) . "\n";

$bancos = \app\models\Banco::find()->asArray()->all();
echo "BANCOS_JSON:" . json_encode($bancos) . "\n";
