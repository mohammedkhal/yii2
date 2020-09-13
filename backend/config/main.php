<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',

    require __DIR__ . '/../../common/config/params-local.php',

    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$container = new \yii\di\Container;

// $container->set('backend\controllers\AdminController', 'backend\Repositories\UserRepository' );

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' => [
            'class' => 'kartik\grid\Module'
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'admin' => [
            'class'=>'yii\web\User',
            'identityClass' => 'common\models\Admin',
            'enableAutoLogin' => false,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
      

    ],
    
    'params' => $params,
];
