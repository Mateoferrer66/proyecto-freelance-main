<?php
// Bootstrap Yii to test the PortafolioSearch
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
$application = new yii\web\Application($config);

echo "=== Testing Portafolio Table Schema ===\n";
try {
    $schema = Yii::$app->db->getTableSchema('portafolio');
    if ($schema) {
        echo "Table schema found. Columns:\n";
        foreach ($schema->columns as $col) {
            echo "  - " . $col->name . " (" . $col->dbType . ")\n";
        }
    } else {
        echo "ERROR: Table schema NOT FOUND!\n";
    }
} catch (Exception $e) {
    echo "ERROR getting schema: " . $e->getMessage() . "\n";
}

echo "\n=== Testing PortafolioSearch::search() ===\n";
try {
    $searchModel = new \app\models\PortafolioSearch();
    echo "PortafolioSearch formName: " . $searchModel->formName() . "\n";
    $dataProvider = $searchModel->search([]);
    echo "Search OK. Total count: " . $dataProvider->getTotalCount() . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Testing PortafolioSearch::search() with typical queryParams ===\n";
try {
    $searchModel = new \app\models\PortafolioSearch();
    $params = ['PortafolioSearch' => ['soc_codigo' => '', 'soc_nombre' => '', 'por_titulo' => '']];
    $dataProvider = $searchModel->search($params);
    echo "Search with params OK. Total count: " . $dataProvider->getTotalCount() . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\nDone.\n";
