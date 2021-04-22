<?php

declare(strict_types=1);

namespace app\tests\fixtures;

use app\models\DestPreset;
use yii\test\ActiveFixture;

final class DestPresetFixture extends ActiveFixture
{
    public $modelClass = DestPreset::class;
    public $depends = [
        JpGienkinFixture::class,
    ];
}
