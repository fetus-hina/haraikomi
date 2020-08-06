<?php

declare(strict_types=1);

namespace app\assets;

use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

class GoogleFontsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];

    public function init()
    {
        $this->css[] = sprintf('https://fonts.googleapis.com/css?%s', http_build_query(
            [
                'family' => join('|', [
                    'Lato',
                ]),
                'display' => 'swap',
            ],
            '',
            '&'
        ));
    }
}
