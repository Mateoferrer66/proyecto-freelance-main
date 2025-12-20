<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

use app\models\Usuario;

echo "Starting Usuario (Socio role) creation/update...\n";

$user = Usuario::findOne(['usu_email' => 'test@test.com']);
if ($user) {
    echo "User test@test.com already exists. Updating password and role.\n";
    $user->usu_password = 'Test1234!';
    $user->usu_rol = Usuario::ROL_SOCIO;
} else {
    echo "Creating new user test@test.com with role Socio.\n";
    $user = new Usuario();
    $user->usu_nombre = 'Socio';
    $user->usu_apellido = 'Test';
    $user->usu_email = 'test@test.com';
    $user->usu_login = 'test@test.com'; // login is email
    $user->usu_password = 'Test1234!';
    $user->usu_rol = Usuario::ROL_SOCIO;
    $user->usu_estado = Usuario::USU_ESTADO_ACTIVO;
    $user->usu_eliminado = 0;
}

if ($user->save()) {
    echo "Success! Credentials:\n";
    echo "Email: test@test.com\n";
    echo "Password: Test1234!\n";
    echo "Role: " . $user->usu_rol . "\n";
} else {
    echo "Error saving user:\n";
    print_r($user->errors);
}
