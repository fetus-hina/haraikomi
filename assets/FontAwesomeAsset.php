<?php

namespace app\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0-11/js/all.min.js',
    ];
}
