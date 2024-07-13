<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\User; // Предположим, что у вас есть модель User
use yii\web\BadRequestHttpException;

class AuthController extends Controller
{
    public function behaviors()
    {
        return [
            'jwtAuth' => [
                'class' => \app\components\JwtAuthFilter::className(),
                'except' => ['login', 'signup'], // Исключить экшены login и signup
            ],
        ];
    }

    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $email = $request->post('email');
        $password = $request->post('password');

        $user = User::findOne(['email' => $email]);

        if ($user && Yii::$app->getSecurity()->validatePassword($password, $user->password_hash)) {
            $token = $this->generateJwtToken($user->id);
            return ['token' => $token];
        }

        return ['error' => 'Invalid login or password'];
    }

    public function actionSignup()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $email = $request->post('email');
        $password = $request->post('password');
        Yii::info('Informational message');
        Yii::info('Informational message');

        if (User::findOne(['email' => $email])) {
            throw new BadRequestHttpException('Email already in use');
        }

        $user = new User();
        $user->email = $email;
        $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);

        if ($user->save()) {
            $token = $this->generateJwtToken($user->id);
            return ['token' => $token];
        }

        throw new BadRequestHttpException('Failed to register user');
    }

    public function actionProfile()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Действие доступно только аутентифицированным пользователям
        // JWT токен уже проверен в фильтре

        return ['message' => 'This is a protected profile data'];
    }

    private function generateJwtToken($userId)
    {
        $payload = [
            'user_id' => $userId,
            'exp' => time() + 3600, // токен будет действителен 1 час
        ];
        return Yii::$app->jwt->encode($payload);
    }
}
