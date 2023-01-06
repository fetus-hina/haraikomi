<?php

declare(strict_types=1);

namespace tests\unit\models;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use app\models\JpBankHtml;
use app\models\JpBankHtmlAccount;

use function array_map;
use function file_get_contents;
use function iterator_to_array;

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

        // $parsed should be JpBankHtmlAccount[]
        $parsed = iterator_to_array($model->parse());
        foreach ($parsed as $item) {
            $this->assertInstanceOf(JpBankHtmlAccount::class, $item);
        }

        $this->assertEquals(
            [
                [
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
                [
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
                [
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
            array_map(
                fn (JpBankHtmlAccount $data) => $data->json,
                $parsed,
            ),
        );
    }
}
