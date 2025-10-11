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
            ['password', 'string', 'min' => 6],
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
