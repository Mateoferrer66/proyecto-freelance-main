<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

use app\models\Socio;

echo "=== CREANDO SOCIO DE PRUEBA ===\n\n";

// Check if socio with this email already exists
$existingSocio = Socio::findOne(['soc_email' => 'socio.prueba@test.com']);

if ($existingSocio) {
    echo "El socio con email socio.prueba@test.com ya existe.\n";
    echo "Actualizando contraseña...\n\n";
    
    $existingSocio->soc_password = 'SocioPrueba123!';
    if ($existingSocio->save(false)) {
        echo "✓ Contraseña actualizada exitosamente!\n\n";
        echo "===========================================\n";
        echo "CREDENCIALES PARA LOGIN:\n";
        echo "===========================================\n";
        echo "Email:      socio.prueba@test.com\n";
        echo "Contraseña: SocioPrueba123!\n";
        echo "===========================================\n";
    } else {
        echo "✗ Error al actualizar contraseña:\n";
        print_r($existingSocio->errors);
    }
} else {
    echo "Creando nuevo socio...\n\n";
    
    // Get the next available socio number
    $maxNumero = Socio::find()->max('soc_numero');
    $nextNumero = $maxNumero ? $maxNumero + 1 : 1000;
    
    $socio = new Socio();
    $socio->soc_numero = $nextNumero;
    $socio->soc_fecha = date('Y-m-d');
    $socio->soc_nombre = 'Socio';
    $socio->soc_apellido = 'De Prueba';
    $socio->soc_apellido1 = 'De Prueba';
    $socio->tdo_id = 1; // Tipo de documento - asumiendo que 1 es DNI
    $socio->soc_numdocide = '87654321X';
    $socio->soc_fecnacimiento = '1985-06-15';
    $socio->soc_sexo = Socio::SOC_SEXO_MASCULINO;
    $socio->soc_email = 'socio.prueba@test.com';
    $socio->soc_numsegsocial = '987654321098';
    $socio->soc_grcotsegsocial = '08'; // Grupo de cotización como string
    $socio->soc_ctabancaria = 'ES9876543210987654321098';
    $socio->soc_password = 'SocioPrueba123!'; // Se cifrará automáticamente
    $socio->soc_estado = Socio::SOC_ESTADO_ACTIVO;
    $socio->soc_eliminado = 0;
    
    if ($socio->save()) {
        echo "✓ Socio creado exitosamente!\n\n";
        echo "===========================================\n";
        echo "CREDENCIALES PARA LOGIN:\n";
        echo "===========================================\n";
        echo "Email:      socio.prueba@test.com\n";
        echo "Contraseña: SocioPrueba123!\n";
        echo "===========================================\n";
        echo "\nDetalles del socio:\n";
        echo "- Nombre: " . $socio->soc_nombre . " " . $socio->soc_apellido . "\n";
        echo "- Número de socio: " . $socio->soc_numero . "\n";
        echo "- Estado: " . $socio->soc_estado . "\n";
    } else {
        echo "✗ Error al crear socio:\n";
        print_r($socio->errors);
    }
}
