<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';

$config = require dirname(__DIR__) . '/config/web.php';

(new yii\web\Application($config));

$table = Yii::$app->db->getTableSchema('socio');
if ($table) {
    echo "Columns for table 'socio':\n";
    foreach ($table->columns as $column) {
        echo "- {$column->name} ({$column->dbType})\n";
    }
} else {
    echo "Table 'socio' not found.\n";
}
