<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class RegistrationForm extends Model
{
    public $name;
    public $surname;
    public $email;
    public $password;

    public $rememberMe = true;

    private $_user = false;


     public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'surname' => 'Surname',
            'email' => 'Email',
            'password' => 'Password',
            'rememberMe' => 'Remember Me',
        ];
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
}
