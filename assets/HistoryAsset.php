<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;

final class HistoryAsset extends AssetBundle
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
    public $css = [
        'css/history.css',
    ];
    /**
     * @var string[]
     */
    public $depends = [
        AppAsset::class,
    ];
}
