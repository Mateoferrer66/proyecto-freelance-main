<?php
/**
 * Script para regenerar contraseñas de usuarios
 * Ejecutar con: docker-compose exec php php fix-passwords.php
 */

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/config/web.php');
new yii\web\Application($config);

use yii\db\Connection;

$db = new Connection([
    'dsn' => 'mysql:host=mysql;dbname=freelance',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
]);

echo "Conectando a la base de datos...\n";

// Obtener todos los usuarios
$usuarios = $db->createCommand('SELECT usu_id, usu_nombre, usu_email, usu_password FROM usuario')->queryAll();

echo "Encontrados " . count($usuarios) . " usuarios.\n\n";

foreach ($usuarios as $usuario) {
    $password = $usuario['usu_password'];
    
    // Verificar si la contraseña ya está hasheada (empieza con $2y$ para bcrypt)
    if (strpos($password, '$2y$') === 0) {
        echo "✓ Usuario '{$usuario['usu_nombre']}' ya tiene contraseña hasheada.\n";
        continue;
    }
    
    // Si no está hasheada, hashearla
    echo "✗ Usuario '{$usuario['usu_nombre']}' tiene contraseña en texto plano: '{$password}'\n";
    
    $hashedPassword = Yii::$app->security->generatePasswordHash($password);
    
    $db->createCommand()->update('usuario', [
        'usu_password' => $hashedPassword
    ], ['usu_id' => $usuario['usu_id']])->execute();
    
    echo "  → Contraseña actualizada. Nueva contraseña: '{$password}' (ahora hasheada)\n\n";
}

echo "\n✓ Proceso completado.\n";
