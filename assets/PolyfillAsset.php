<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;

class PolyfillAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/polyfill.js',
    ];
    public $depends = [
    ];
}
