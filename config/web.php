<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
//        'jwt' => [
//            'class' => 'app\components\JwtHelper',
//            'key' => 'your-secret-key', // замените на ваш секретный ключ
//        ],
//        'security' => [
//            'passwordHashStrategy' => 'password_hash',
//        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'kZlQje27j-rwUxgjflFp2dNMvoO6GN0V',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'trace', 'info'],
                    'logVars' => [],
                    'logFile' => '@runtime/webapp/logs/myfile.log',
                ],
            ],
        ],
        'db' => require __DIR__ . '/db.php',
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'get-wishlist' => 'wishlist/get-wishlist',
                'update-wishlist' => 'wishlist/update-wishlist',
                'create-wish' => 'wishlist/create-wish',
                'delete-wish' => 'wishlist/delete-wish',
                'my-error' => 'wishlist/my-error',
                'phpinfo' => 'site/phpinfo',
                'friends' => 'site/friends',
                'cabinet' => 'site/cabinet',
                'entry' => 'site/entry',
                'reg' => 'site/reg',
                'POST login' => 'auth/login',
                'POST signup' => 'auth/signup',
                'GET profile' => 'auth/profile',
                'GET wish' => 'wish/get-wish',
                'POST wish' => 'wish/add-wish',
                'POST wish' => 'wish/create-wish',
            ],
        ]
    ],
    'controllerMap' => [
        'user' => 'app\controllers\UserController',
        'wishlist' => 'app\controllers\WishlistController',
        'wish' => 'app\controllers\WishController',
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
