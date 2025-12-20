<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\validators\StringValidator;

/**
 * Reset password form
 */
class ResetPasswordForm extends Model
{
    public $password;
    private $_user;

    public function __construct($user = null, $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['password', 'required'],
            // mínimo 8 caracteres
            ['password', 'string', 'min' => 8],
            // al menos una mayúscula, una minúscula, un número y un carácter especial
            ['password', 'match', 'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/',
                'message' => 'La contraseña debe tener al menos 8 caracteres e incluir una mayúscula, una minúscula, un número y un carácter especial.'
            ],
        ];
    }

    public function resetPassword()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->_user;
        if (!$user) {
            return false;
        }

        $user->usu_password = $this->password;
        return $user->save(false);
    }
}
