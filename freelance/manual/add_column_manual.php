<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

$sql = "ALTER TABLE usuario ADD COLUMN usu_rol VARCHAR(20) AFTER usu_password";
try {
    Yii::$app->db->createCommand($sql)->execute();
    echo "Column usu_rol added successfully.\n";
} catch (\Exception $e) {
    echo "Error adding column: " . $e->getMessage() . "\n";
}
