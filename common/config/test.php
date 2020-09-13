<?php
return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
        ],
        'admin' => [
            'class' => 'yii\web\Admin',
            'identityClass' => 'common\models\Admin',
        ],
    ],
];
