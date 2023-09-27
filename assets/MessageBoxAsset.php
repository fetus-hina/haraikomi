<?php

declare(strict_types=1);

namespace app\assets;

use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

final class MessageBoxAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $basePath = '@webroot';

    /**
     * @var string
     */
    public $baseUrl = '@web';

    /**
     * @var string[]
     */
    public $js = [
        'js/messagebox.js',
    ];

    /**
     * @var string[]
     */
    public $depends = [
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        JqueryAsset::class,
    ];
}
