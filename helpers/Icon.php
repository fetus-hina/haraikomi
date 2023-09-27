<?php

declare(strict_types=1);

namespace app\helpers;

use LogicException;
use TypeError;
use Yii;
use app\assets\BootstrapIconsAsset;
use yii\helpers\Html;
use yii\web\AssetBundle;
use yii\web\View;

use function is_string;

final class Icon
{
    // dialogError
    // dialogInfo
    // dialogWarning
    // disaster
    // dismiss
    // download
    // filePdf
    // github
    // help
    // load
    // save
    // twitter

    public static function dialogError(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-exclamation-octagon');
    }

    public static function dialogInfo(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-info-circle');
    }

    public static function dialogWarning(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-exclamation-triangle');
    }

    public static function disaster(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-cloud-rain-heavy');
    }

    public static function dismiss(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-x-lg');
    }

    public static function download(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-download');
    }

    public static function filePdf(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-file-earmark-pdf-fill');
    }

    public static function github(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-github');
    }

    public static function help(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-question-circle');
    }

    public static function load(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-box-arrow-up');
    }

    public static function save(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-box-arrow-in-down');
    }

    public static function twitter(): string
    {
        return self::renderIcon(IconSource::BOOTSTRAP_ICONS, 'bi-twitter-x');
    }

    private static function renderIcon(IconSource $source, mixed $data): string
    {
        self::registerAsset($source);
        return self::renderIconImpl($source, $data);
    }

    private static function registerAsset(IconSource $source): AssetBundle
    {
        $view = Yii::$app->view;
        if (!$view instanceof View) {
            throw new LogicException();
        }

        return match ($source) {
            IconSource::BOOTSTRAP_ICONS => BootstrapIconsAsset::register($view),
        };
    }

    private static function renderIconImpl(IconSource $source, mixed $data): string
    {
        return match ($source) {
            IconSource::BOOTSTRAP_ICONS => self::renderBootstrapIcon($data),
        };
    }

    private static function renderBootstrapIcon(mixed $class): string
    {
        if (!is_string($class)) {
            throw new TypeError();
        }

        return Html::tag('span', '', [
            'class' => ['bi', $class],
        ]);
    }
}
