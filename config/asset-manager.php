<?php

declare(strict_types=1);

use ParagonIE\ConstantTime\Base32;
use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\validators\PunycodeAsset;
use yii\web\JqueryAsset;
use yii\widgets\MaskedInputAsset;
use yii\widgets\PjaxAsset;

return [
    'linkAssets' => true,
    'appendTimestamp' => false,
    'bundles' => [
        BootstrapAsset::class => [
            'sourcePath' => '@npm/@fetus-hina/fetus.css/dist',
            'css' => [
                'bootstrap-lineseedjp.min.css',
            ],
        ],
        BootstrapPluginAsset::class => [
            'sourcePath' => '@npm/bootstrap/dist',
            'js' => [
                'js/bootstrap.bundle.min.js',
            ],
        ],
        PunycodeAsset::class => [
            'sourcePath' => '@npm/punycode',
        ],
        JqueryAsset::class => [
            'sourcePath' => '@npm/jquery/dist',
            'js' => [
                'jquery.min.js',
            ],
        ],
        MaskedInputAsset::class => [
            'sourcePath' => '@npm/inputmask/dist',
        ],
        PjaxAsset::class => [
            'sourcePath' => '@npm/yii2-pjax',
        ],
    ],
    'hashCallback' => function (string $path): string {
        $pathParts = [];

        static $appPath = null;
        if (!$appPath) {
            $appPath = realpath((string)Yii::getAlias('@app'));
        }
        if ($appPath) {
            $pathParts[] = substr(
                Base32::encodeUnpadded(
                    hash('sha256', $appPath, true),
                ),
                0,
                8,
            );
        }

        $pathParts[] = substr(
            Base32::encodeUnpadded(
                hash(
                    'sha256',
                    is_file($path) ? dirname($path) : $path,
                    true,
                ),
            ),
            0,
            8,
        );

        return implode('/', $pathParts);
    },
];
