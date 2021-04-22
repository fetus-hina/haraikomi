<?php

declare(strict_types=1);

namespace app\assets;

use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

final class MessageBoxAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/messagebox.js',
    ];
    public $depends = [
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        FontAwesomeAsset::class,
        JqueryAsset::class,
        PolyfillAsset::class,
    ];
}
