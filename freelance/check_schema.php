<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

$tableSchema = Yii::$app->db->schema->getTableSchema('usuario');
if ($tableSchema) {
    echo "Columns in usuario table:\n";
    foreach ($tableSchema->columns as $column) {
        echo $column->name . "\n";
    }
} else {
    echo "Table usuario not found.\n";
}
