<?php
/**
 * Script para crear un nuevo usuario administrador
 * Ejecutar con: docker-compose exec php php create-admin.php
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

echo "Creando nuevo usuario administrador...\n\n";

// Datos del nuevo usuario
$nombre = 'Administrador';
$login = 'admin';
$email = 'admin@freelance.com';
$password = 'admin123';  // Puedes cambiar esta contraseña

// Verificar si el email ya existe
$existingUser = $db->createCommand('SELECT usu_id FROM usuario WHERE usu_email = :email', [
    ':email' => $email
])->queryOne();

if ($existingUser) {
    echo "⚠ El usuario con email '{$email}' ya existe.\n";
    echo "Actualizando contraseña...\n";
    
    $hashedPassword = Yii::$app->security->generatePasswordHash($password);
    
    $db->createCommand()->update('usuario', [
        'usu_nombre' => $nombre,
        'usu_login' => $login,
        'usu_password' => $hashedPassword
    ], ['usu_email' => $email])->execute();
    
    echo "✓ Usuario actualizado correctamente.\n";
} else {
    // Crear nuevo usuario
    $hashedPassword = Yii::$app->security->generatePasswordHash($password);
    
    $db->createCommand()->insert('usuario', [
        'usu_nombre' => $nombre,
        'usu_login' => $login,
        'usu_email' => $email,
        'usu_password' => $hashedPassword,
        'usu_estado' => 'Activo'
    ])->execute();
    
    echo "✓ Usuario creado correctamente.\n";
}

echo "\n====================================\n";
echo "Credenciales de acceso:\n";
echo "====================================\n";
echo "Login:    {$login}\n";
echo "Email:    {$email}\n";
echo "Password: {$password}\n";
echo "====================================\n";
