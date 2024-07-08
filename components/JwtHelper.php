<?php
namespace app\components;

use Firebase\JWT\JWT;
use Yii;
use yii\base\Component;
use yii\web\UnauthorizedHttpException;

class JwtHelper extends Component
{
    public $key;

    public function init()
    {
        parent::init();
        if ($this->key === null) {
            throw new \Exception('JWT key must be set');
        }
    }

    public function encode(array $payload)
    {
        return JWT::encode($payload, $this->key);
    }

    public function decode($token)
    {
        try {
            return JWT::decode($token, $this->key, ['HS256']);
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Invalid token');
        }
    }
}
