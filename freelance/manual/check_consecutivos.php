<?php

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/config/web.php');

(new yii\web\Application($config));

$consecutivos = \app\models\Consecutivo::find()->all();

foreach ($consecutivos as $c) {
    echo "ID: " . $c->con_id . " - Serie: " . $c->con_serie . " - Current: " . $c->con_consecutivo . "\n";
}
