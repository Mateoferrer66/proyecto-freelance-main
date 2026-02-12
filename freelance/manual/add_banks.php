<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';

(new yii\web\Application($config));

$banksToAdd = ['Bankinter', 'CaixaBank'];

foreach ($banksToAdd as $bankName) {
    $banco = \app\models\Banco::findOne(['ban_nombre' => $bankName]);
    if (!$banco) {
        $banco = new \app\models\Banco();
        $banco->ban_nombre = $bankName;
        $banco->ban_numcuenta = 'ES000000000000000000'; // Default placeholder
        $banco->ban_titular_tarjeta = 'Titular'; // Default placeholder
        if ($banco->save()) {
            echo "Added bank: $bankName\n";
        } else {
            echo "Failed to add bank: $bankName\n";
            print_r($banco->getErrors());
        }
    } else {
        echo "Bank already exists: $bankName\n";
    }
}
