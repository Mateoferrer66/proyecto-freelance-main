<?php
// Test script to verify PortafolioSearch works
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

try {
    echo "Testing PortafolioSearch...\n";
    echo str_repeat("-", 50) . "\n";
    
    $searchModel = new app\models\PortafolioSearch();
    $dataProvider = $searchModel->search([]);
    
    echo "✅ PortafolioSearch instantiated successfully\n";
    echo "Total portafolio records: " . $dataProvider->getTotalCount() . "\n";
    
    $models = $dataProvider->getModels();
    if (empty($models)) {
        echo "No portafolio records found (this is OK for a new table)\n";
    } else {
        echo "\nFirst record:\n";
        $first = $models[0];
        echo "  ID: " . $first->por_id . "\n";
        echo "  Título: " . $first->por_titulo . "\n";
    }
    
    echo "\n✅ All tests passed! PortafolioSearch is working correctly.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
