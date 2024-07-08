<?php

namespace app\controllers;

use yii\web\Controller;
use app\components\JwtAuthFilter;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'jwtAuth' => [
                'class' => JwtAuthFilter::className(),
                'except' => ['login'], // Исключить экшен login
            ],
        ];
    }

    public function actionLogin()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Здесь должна быть логика проверки логина и пароля пользователя
        // Например, вы нашли пользователя по его email и проверили его пароль

        $user = ['id' => 1, 'username' => 'demo']; // Пример пользователя

        if ($user) {
            $payload = [
                'user_id' => $user['id'],
                'exp' => time() + 3600, // токен будет действителен 1 час
            ];
            $token = Yii::$app->jwt->encode($payload);

            return ['token' => $token];
        }

        return ['error' => 'Invalid login or password'];
    }

    public function actionProfile()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Действие доступно только аутентифицированным пользователям
        // JWT токен уже проверен в фильтре

        return ['message' => 'This is a protected profile data'];
    }
}
