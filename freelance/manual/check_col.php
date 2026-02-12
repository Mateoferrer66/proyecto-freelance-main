<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';

(new yii\web\Application($config));

$schema = Yii::$app->db->getTableSchema('usuario');
if ($schema->getColumn('usu_rol')) {
    echo "Column usu_rol exists.\n";
} else {
    echo "Column usu_rol does NOT exist.\n";
}
