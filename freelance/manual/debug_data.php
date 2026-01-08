<?php

// Define YII constants
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';

// Mock application to use DB component
new yii\web\Application($config);

echo "Checking Database Data...\n";

// 1. Check Facturas Count
$countFacturas = \app\models\Factura::find()->count();
echo "Total Facturas: $countFacturas\n";

// 2. Check 2026 Facturas
$count2026 = \app\models\Factura::find()->where(['like', 'fac_fecha', '2026'])->count();
echo "Facturas 2026: $count2026\n";

// 3. Check Top Socios Data
$topSocios = \app\models\Factura::find()
    ->alias('f')
    ->select(['s.soc_nombre', 'SUM(f.fac_total) as total', 'f.soc_id'])
    ->joinWith('soc s')
    ->groupBy('f.soc_id')
    ->orderBy(['total' => SORT_DESC])
    ->limit(5)
    ->asArray()
    ->all();
echo "Top Socios Count: " . count($topSocios) . "\n";
print_r($topSocios);

// 4. Check New Clients Data (First Invoice)
$currentYear = date('Y'); // 2026 based on metadata
$sql = "
    SELECT MONTH(first_date) as mes, COUNT(*) as total
    FROM (
        SELECT cli_id, MIN(fac_fecha) as first_date
        FROM factura
        GROUP BY cli_id
    ) as client_dates
    WHERE YEAR(first_date) = :year
    GROUP BY mes
    ORDER BY mes ASC
";
$clientsAcquisition = Yii::$app->db->createCommand($sql)
    ->bindValue(':year', $currentYear)
    ->queryAll();

echo "New Clients Data (2026):\n";
print_r($clientsAcquisition);

// 5. Check clients with ANY first date
$sqlAll = "
    SELECT YEAR(first_date) as year, COUNT(*) as total
    FROM (
        SELECT cli_id, MIN(fac_fecha) as first_date
        FROM factura
        GROUP BY cli_id
    ) as client_dates
    GROUP BY year
    ORDER BY year DESC
";
$yearsAcquisition = Yii::$app->db->createCommand($sqlAll)->queryAll();
echo "Acquisition by Year summary:\n";
print_r($yearsAcquisition);
