<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;

final class PostalCodeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/postalcode.js',
    ];
    public $depends = [
        AppAsset::class,
        MessageBoxAsset::class,
    ];
}
