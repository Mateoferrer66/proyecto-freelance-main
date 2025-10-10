<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read Usuario|null $user
 *
 */
class LoginForm extends Model
{
    public $usu_login;
    public $usu_password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['usu_login', 'usu_password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['usu_password', 'validatePassword'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'usu_login' => 'Usuario',
            'usu_password' => 'Contraseña',
            'rememberMe' => 'Recordarme',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->usu_password)) {
                $this->addError($attribute, 'Usuario o contraseña incorrectos.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return Usuario|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Usuario::findByUsername($this->usu_login);
        }

        return $this->_user;
    }
}
