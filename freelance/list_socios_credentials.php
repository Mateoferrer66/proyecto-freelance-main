<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

use app\models\Socio;

echo "=== SOCIOS DISPONIBLES PARA LOGIN ===\n\n";

// Find all active socios with email and password
$socios = Socio::find()
    ->where(['soc_estado' => Socio::SOC_ESTADO_ACTIVO, 'soc_eliminado' => 0])
    ->andWhere(['IS NOT', 'soc_email', null])
    ->andWhere(['<>', 'soc_email', ''])
    ->andWhere(['IS NOT', 'soc_password', null])
    ->andWhere(['<>', 'soc_password', ''])
    ->all();

if (count($socios) > 0) {
    echo "Se encontraron " . count($socios) . " socio(s) con credenciales:\n\n";
    
    foreach ($socios as$socio) {
        echo "----------------------------------------\n";
        echo "Nombre: " . $socio->soc_nombre . " " . $socio->soc_apellido . "\n";
        echo "Email: " . $socio->soc_email . "\n";
        echo "Número de Socio: " . $socio->soc_numero . "\n";
        echo "Estado: " . $socio->soc_estado . "\n";
        echo "----------------------------------------\n\n";
    }
    
    echo "\nNOTA: Las contraseñas están cifradas en la base de datos.\n";
    echo "Si necesitas crear un nuevo socio con credenciales conocidas,\n";
    echo "puedo crear uno para ti.\n";
} else {
    echo "No se encontraron socios con credenciales configuradas.\n";
    echo "Creando un socio de prueba...\n\n";
    
    // Crear un socio de prueba
    $socio = new Socio();
    $socio->soc_numero = 9999;
    $socio->soc_fecha = date('Y-m-d');
    $socio->soc_nombre = 'Socio';
    $socio->soc_apellido = 'Prueba';
    $socio->soc_apellido1 = 'Prueba';
    $socio->tdo_id = 1; // Assuming 1 is a valid document type
    $socio->soc_numdocide = '12345678A';
    $socio->soc_fecnacimiento = '1990-01-01';
    $socio->soc_sexo = Socio::SOC_SEXO_MASCULINO;
    $socio->soc_email = 'socio@test.com';
    $socio->soc_numsegsocial = '123456789012';
    $socio->soc_ctabancaria = 'ES1234567890123456789012';
    $socio->soc_password = 'Socio123!'; // Will be hashed automatically
    $socio->soc_estado = Socio::SOC_ESTADO_ACTIVO;
    
    if ($socio->save()) {
        echo "✓ Socio creado exitosamente!\n\n";
        echo "Credenciales:\n";
        echo "Email: socio@test.com\n";
        echo "Contraseña: Socio123!\n";
    } else {
        echo "✗ Error al crear socio:\n";
        print_r($socio->errors);
    }
}
