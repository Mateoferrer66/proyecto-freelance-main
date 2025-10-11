<?php
/* @var $this yii\web\View */
/* @var $user app\models\Usuario */
/* @var $resetLink string */
?>

<p>Hola <?= htmlspecialchars($user->usu_nombre) ?>,</p>

<p>Recibimos una solicitud para restablecer la contraseña de su cuenta. Haga clic en el siguiente enlace para establecer una nueva contraseña (válido por 1 hora):</p>

<?php
// No alteramos el href: escapamos correctamente y añadimos un fallback en texto plano
// para que, si el cliente de correo rompe el enlace al guardarlo en .eml, el usuario
// pueda copiar/pegar la URL completa.
$escaped = htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8');
?>
<p><a href="<?= $escaped ?>"><?= $escaped ?></a></p>

<p>Si al hacer clic el enlace no funciona, copie y pegue la siguiente URL en su navegador:</p>
<pre style="word-break:break-all;"><?= $escaped ?></pre>

<p>Si usted no solicitó este cambio, puede ignorar este mensaje.</p>

<p>Saludos,<br/>El equipo</p>
