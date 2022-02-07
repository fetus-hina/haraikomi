<?php

declare(strict_types=1);

namespace tests\unit\models;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use app\models\JpBankHtml;

final class JpBankHtmlTest extends Unit
{
    protected UnitTester $tester;

    public function testParse(): void
    {
        $model = Yii::createObject([
            'class' => JpBankHtml::class,
            'html' => (string)file_get_contents(__DIR__ . '/../../fixtures/data/test.html'),
        ]);
        $this->assertTrue($model->validate());

        $this->assertEquals(
            [
                (object)[
                    'disaster' => '災害名1',
                    'accountName' => '支援団体1-1',
                    'account' => [
                        1,
                        1,
                        123456,
                    ],
                    'start' => '2021-01-01',
                    'end' => '2022-12-31',
                ],
                (object)[
                    'disaster' => '災害名1',
                    'accountName' => '支援団体1-2',
                    'account' => [
                        2,
                        1,
                        123456,
                    ],
                    'start' => '2022-01-01',
                    'end' => '2022-12-31',
                ],
                (object)[
                    'disaster' => '災害名2',
                    'accountName' => '支援団体2-1',
                    'account' => [
                        1,
                        2,
                        123456,
                    ],
                    'start' => '2020-01-01',
                    'end' => '2020-12-31',
                ],
            ],
            iterator_to_array($model->parse()),
        );
    }
}
