<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => '\\app\\models\\Usuario', 'targetAttribute' => 'usu_email', 'filter' => ['usu_eliminado' => 0], 'message' => 'No existe ning\u00fan usuario con ese email.'],
        ];
    }

    /**
     * Sends an email with a link, containing a signed token, to reset the password.
     * The token is generated without modifying the database: it encodes user id and expiry and is HMAC-signed using cookieValidationKey.
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = Usuario::findOne(['usu_email' => $this->email, 'usu_eliminado' => 0]);
        if (!$user) {
            return false;
        }

        $token = $this->generateToken($user->usu_id);

        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $token]);

        return Yii::$app->mailer->compose('passwordResetToken', ['user' => $user, 'resetLink' => $resetLink])
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo($this->email)
            ->setSubject('Solicitud de reinicio de contraseña')
            ->send();
    }

    private function generateToken($userId)
    {
        $secret = Yii::$app->request->cookieValidationKey ?? Yii::$app->params['cookieValidationKey'] ?? Yii::$app->params['secret'] ?? '';
        if (empty($secret)) {
            // fallback to application cookieValidationKey from config/request
            $secret = Yii::$app->request->cookieValidationKey;
        }

        $expiry = time() + 3600; // 1 hora
        $data = $userId . '|' . $expiry;
        $sig = hash_hmac('sha256', $data, $secret);
        return rtrim(strtr(base64_encode($data . '|' . $sig), '+/', '-_'), '=');
    }
}
