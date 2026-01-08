<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

use app\models\Socio;

echo "Checking existing Socios with passwords...\n\n";

// Find all active socios with email and password
$socios = Socio::find()
    ->where(['soc_estado' => Socio::SOC_ESTADO_ACTIVO, 'soc_eliminado' => 0])
    ->andWhere(['IS NOT', 'soc_email', null])
    ->andWhere(['<>', 'soc_email', ''])
    ->all();

echo "Found " . count($socios) . " active socios with email.\n\n";

$sociosWithPassword = 0;
foreach ($socios as $socio) {
    if (!empty($socio->soc_password)) {
        $sociosWithPassword++;
        echo "Socio #" . $socio->soc_numero . ": " . $socio->soc_nombre . " " . $socio->soc_apellido . "\n";
        echo "  Email: " . $socio->soc_email . "\n";
        echo "  Has password: Yes\n\n";
    }
}

echo "\nTotal socios with password configured: " . $sociosWithPassword . "\n";

if ($sociosWithPassword === 0) {
    echo "\nNo socios found with passwords. Would you like to create one?\n";
}
