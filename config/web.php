<?php
declare(strict_types=1);

use app\models\User;
use yii\caching\FileCache;
use yii\debug\Module as DebugModule;
use yii\log\FileTarget;

$config = [
    'id' => 'haraikomi',
    'name' => '払込取扱票印刷用PDF作成機',
    'basePath' => dirname(__DIR__),
    'language' => 'ja-JP',
    'bootstrap' => ['log'],
    'aliases' => [
        // '@bower' => '@vendor/bower-asset',
        '@npm' => '@app/node_modules',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => include(__DIR__ . '/cookie.php'),
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
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
        'assetManager' => require(__DIR__ . '/asset-manager.php'),
    ],
    'params' => require(__DIR__ . '/params.php'),
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => DebugModule::class,
        'allowedIPs' => file_exists(__DIR__ . '/debug-ips.php')
            ? require(__DIR__ . '/debug-ips.php')
            : ['127.0.0.1', '::1'],
    ];
}

return $config;
