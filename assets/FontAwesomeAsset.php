<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;

final class FontAwesomeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js',
    ];
}
