<?php

declare(strict_types=1);

namespace tests\unit\models;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use app\models\PostalCodeApiForm;

final class PostalCodeApiFormTest extends Unit
{
    protected UnitTester $tester;

    /**
     * @dataProvider getValidateTestData
     */
    public function testValidate(bool $expected, string $code): void
    {
        $model = Yii::createObject(PostalCodeApiForm::class);
        $model->code = $code;
        $this->assertEquals($expected, $model->validate());
    }

    public function getValidateTestData(): array
    {
        return [
            [false, ''],
            [true, '0000000'],
            [true, '9999999'],
            [false, '000-0000'],
        ];
    }
}
