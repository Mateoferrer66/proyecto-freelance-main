<?php
// add_columns.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
$app = new yii\web\Application($config);

$db = Yii::$app->db;

/*
 * Table: factura
 * Columns: fac_numero_pedido (VARCHAR 45), fac_archivo (VARCHAR 255)
 */
echo "Checking table 'factura'...\n";
$schema = $db->getTableSchema('factura');
if (!$schema->getColumn('fac_numero_pedido')) {
    echo "Adding 'fac_numero_pedido' to 'factura'...\n";
    $db->createCommand()->addColumn('factura', 'fac_numero_pedido', "VARCHAR(45) NULL DEFAULT NULL AFTER fac_numero")->execute();
} else {
    echo "'fac_numero_pedido' already exists.\n";
}

if (!$schema->getColumn('fac_archivo')) {
    echo "Adding 'fac_archivo' to 'factura'...\n";
    $db->createCommand()->addColumn('factura', 'fac_archivo', "VARCHAR(255) NULL DEFAULT NULL AFTER fac_observaciones")->execute();
} else {
    echo "'fac_archivo' already exists.\n";
}

/*
 * Table: presupuesto
 * Columns: pre_numero_pedido (VARCHAR 45), pre_archivo (VARCHAR 255)
 */
echo "Checking table 'presupuesto'...\n";
$schema = $db->getTableSchema('presupuesto');
if (!$schema->getColumn('pre_numero_pedido')) {
    echo "Adding 'pre_numero_pedido' to 'presupuesto'...\n";
    $db->createCommand()->addColumn('presupuesto', 'pre_numero_pedido', "VARCHAR(45) NULL DEFAULT NULL AFTER pre_numero")->execute();
} else {
    echo "'pre_numero_pedido' already exists.\n";
}

if (!$schema->getColumn('pre_archivo')) {
    echo "Adding 'pre_archivo' to 'presupuesto'...\n";
    $db->createCommand()->addColumn('presupuesto', 'pre_archivo', "VARCHAR(255) NULL DEFAULT NULL AFTER pre_observaciones")->execute();
} else {
    echo "'pre_archivo' already exists.\n";
}

echo "Done.\n";
