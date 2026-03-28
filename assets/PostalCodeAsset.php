<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;

final class PostalCodeAsset extends AssetBundle
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
        'js/postalcode.js',
    ];

    /**
     * @var class-string<AssetBundle>[]
     */
    public $depends = [
        AppAsset::class,
        MessageBoxAsset::class,
    ];
}
