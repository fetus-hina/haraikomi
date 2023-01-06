<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;

final class PolyfillAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $basePath = '@webroot';

    /**
     * @inheritdoc
     */
    public $baseUrl = '@web';

    /**
     * @inheritdoc
     */
    public $css = [];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/polyfill.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
    ];
}
