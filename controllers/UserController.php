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
        User::createUser($username, $email, $password);

        return $this->redirect(['site/index']);
    }
}
