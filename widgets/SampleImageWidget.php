<?php

declare(strict_types=1);

namespace app\widgets;

use yii\base\Widget;
use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\Html;

final class SampleImageWidget extends Widget
{
    private const WIDTH = 609;
    private const HEIGHT = 384;

    public function run(): string
    {
        BootstrapAsset::register($this->view);
        $id = $this->id;

        // https://easings.net/#easeInOutQuart
        $easeInOutQuart = 'cubic-bezier(0.76, 0, 0.24, 1)';
        $this->view->registerCss($this->renderCss([
            "#{$id}" => [
                '--bs-aspect-ratio' => sprintf('%.9f%%', self::HEIGHT / self::WIDTH * 100),
                'max-width' => sprintf('%dpx', self::WIDTH),
            ],
            "#{$id}-sample" => [
                'opacity' => '0',
                'transition' => "opacity 0.25s {$easeInOutQuart} 0s",
            ],
            "#{$id}-sample:hover" => [
                'opacity' => '1',
            ],
        ]));

        return Html::tag(
            'div',
            implode('', [
                Html::img('@web/images/haraikomi.png', [
                    'alt' => '',
                    'title' => '',
                ]),
                Html::img('@web/images/sample.png', [
                    'alt' => '',
                    'id' => sprintf('%s-sample', $id),
                    'title' => '',
                ]),
            ]),
            [
                'class' => 'ratio',
                'id' => $id,
            ]
        );
    }

    /** @param array<string, array<string, string>> $css */
    private function renderCss(array $css): string
    {
        return implode('', array_map(
            fn (string $selector, array $css): string => vsprintf('%s{%s}', [
                $selector,
                Html::cssStyleFromArray($css),
            ]),
            array_keys($css),
            array_values($css),
        ));
    }
}
