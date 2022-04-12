<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;

final class HistoryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/history.css',
    ];
    public $depends = [
        AppAsset::class,
    ];
}
