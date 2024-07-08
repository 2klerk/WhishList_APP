<?php

namespace app\components;

use Yii;
use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;

class JwtAuthFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        $headers = Yii::$app->request->headers;
        $authHeader = $headers->get('Authorization');
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $token = $matches[1];
            try {
                Yii::$app->jwt->decode($token);
                return parent::beforeAction($action);
            } catch (UnauthorizedHttpException $e) {
                throw new UnauthorizedHttpException('Invalid token');
            }
        }

        throw new UnauthorizedHttpException('Authorization header not found');
    }
}
