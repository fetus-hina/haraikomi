<?php

declare(strict_types=1);

namespace app\tests\fixtures;

use app\models\JpGienkin;
use yii\test\ActiveFixture;

final class JpGienkinFixture extends ActiveFixture
{
    public $modelClass = JpGienkin::class;
    public $depends = [];
}
