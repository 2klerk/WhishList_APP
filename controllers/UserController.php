<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;

class UserController extends Controller
{
    // Действие для создания пользователя
    public function actionCreate()
    {
        $username = Yii::$app->request->post('username');
        $email = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password');

        // Вызываем метод модели для создания пользователя
        User::createUser($username, $email, $password);

        return $this->redirect(['site/index']); // замените на нужный адрес
    }
    // Дополнительные действия могут быть добавлены здесь, если необходимо
}
