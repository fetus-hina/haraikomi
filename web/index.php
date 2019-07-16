<?php
define('K_PATH_FONTS', __DIR__ . '/../resources/fonts/_tcpdf/'); // phpcs:disable

if (!file_exists(__DIR__ . '/../.production')) {
    defined('YII_DEBUG') or define('YII_DEBUG', true); // phpcs:disable
    defined('YII_ENV') or define('YII_ENV', 'dev'); // phpcs:disable
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
