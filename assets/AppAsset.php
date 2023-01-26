<?php

declare(strict_types=1);

namespace app\assets;

use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

final class AppAsset extends AssetBundle
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
        'js/save.js',
        'js/gienkin.js',
    ];

    /**
     * @var string[]
     */
    public $depends = [
        BackToTopAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        YiiAsset::class,
    ];
}
