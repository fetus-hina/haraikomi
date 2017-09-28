<?php
$config = [
    'id' => 'haraikomi',
    'name' => '払込取扱票印刷用PDF作成機',
    'basePath' => dirname(__DIR__),
    'language' => 'ja-JP',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => include(__DIR__ . '/cookie.php'),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => require(__DIR__ . '/params.php'),
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => file_exists(__DIR__ . '/debug-ips.php')
            ? require(__DIR__ . '/debug-ips.php')
            : ['127.0.0.1', '::1'],
    ];
}

return $config;
