<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'users'; // Название таблицы в базе данных
    }

    public function rules(): array
    {
        return [
            [['name', 'surname', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This email address has already been taken.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Name',
            'surname' => 'Surname',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }
}
