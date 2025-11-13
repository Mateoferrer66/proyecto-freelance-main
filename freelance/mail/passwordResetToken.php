<?php
/* @var $this yii\web\View */
/* @var $user app\models\Usuario */
/* @var $resetLink string */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .message {
            font-size: 14px;
            color: #555;
            margin: 20px 0;
            line-height: 1.6;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .reset-button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 40px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 16px;
        }
        .fallback-url {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 4px;
            word-break: break-all;
            font-size: 12px;
            color: #555;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #856404;
            margin: 20px 0;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px 30px;
            border-top: 1px solid #ecf0f1;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .divider {
            height: 1px;
            background-color: #ecf0f1;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recuperación de Contraseña</h1>
        </div>
        <div class="content">
            <div class="greeting">
                Hola <strong><?= htmlspecialchars($user->usu_nombre) ?></strong>,
            </div>
            <div class="message">
                Recibimos una solicitud para restablecer la contraseña de su cuenta. Haga clic en el botón de abajo para establecer una nueva contraseña.
            </div>
            <p style="color: #e74c3c; font-size: 13px;">
                <strong>Importante:</strong> Este enlace es válido por <strong>1 hora</strong>. Después de este tiempo, deberá solicitar uno nuevo.
            </p>
            <div class="button-container">
                <?php
                $escaped = htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8');
                ?>
                <a href="<?= $escaped ?>" class="reset-button">Restablecer Contraseña</a>
            </div>
            <div class="divider"></div>
            <div class="message" style="font-size: 13px;">
                Si al hacer clic el botón no funciona, copie y pegue la siguiente URL en su navegador:
            </div>
            <div class="fallback-url">
                <?= $escaped ?>
            </div>
            <div class="warning">
                <strong>⚠️ Seguridad:</strong> Si usted no solicitó este cambio, puede ignorar este mensaje. Su cuenta permanecerá segura.
            </div>
        </div>
        <div class="footer">
            <p>Este es un correo automático. Por favor, no responda a este mensaje.</p>
            <p>© <?= date('Y') ?> Sistema de Facturación Freelance. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
