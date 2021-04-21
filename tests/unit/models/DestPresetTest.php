<?php

declare(strict_types=1);

namespace tests\unit\models;

use Codeception\Test\Unit;
use DateTimeImmutable;
use UnitTester;
use Yii;
use app\models\DestPreset;
use app\models\JpGienkin;
use app\tests\fixtures\DestPresetFixture;

class DestPresetTest extends Unit
{
    protected UnitTester $tester;

    public function testValidate(): void
    {
        $model = Yii::createObject([
            'class' => DestPreset::class,
            'id' => 0x7fffffff,
            'name' => 'テスト用振込先',
            'account1' => 1234,
            'account2' => 9,
            'account3' => 567890,
            'account_name' => '振込先名義',
            'valid_from' => gmmktime(9, 0, 0, 1, 1, 2000),
            'valid_to' => gmmktime(9, 0, 0, 1, 1, 2100),
        ]);
        $this->assertTrue($model->validate());
    }

    public function testPreset(): void
    {
        $this->tester->haveFixtures([
            DestPresetFixture::class,
        ]);

        $query = DestPreset::find()
            ->nonGienkin()
            ->valid(new DateTimeImmutable('2021-04-01T00:00:00+09:00'))
            ->orderBy(['id' => SORT_ASC]);

        $this->assertEquals(2, $query->count());

        $model = $query->one(); // will fetch ID=6
        $this->assertInstanceOf(DestPreset::class, $model);
        $attrs = [
            'id' => 6,
            'name' => 'プリセット1',
            'account1' => 66666,
            'account2' => 6,
            'account3' => 666666,
            'account_name' => 'プリセット口座1',
            'jp_gienkin_id' => null,
        ];
        foreach ($attrs as $attr => $expect) {
            $this->assertEquals($expect, $model->$attr);
        }

        $this->assertNull($model->jpGienkin);
    }

    public function testGienkin(): void
    {
        $this->tester->haveFixtures([
            DestPresetFixture::class,
        ]);

        $this->assertEquals(5, DestPreset::find()->gienkin()->count());

        $query = DestPreset::find()
            ->gienkin()
            ->valid(new DateTimeImmutable('2021-04-01T00:00:00+09:00'))
            ->orderBy(['id' => SORT_ASC]);
        $this->assertEquals(3, $query->count());

        $model = $query->one(); // will fetch ID=1
        $this->assertInstanceOf(DestPreset::class, $model);
        $attrs = [
            'id' => 1,
            'name' => '義援金1',
            'account1' => 11111,
            'account2' => 1,
            'account3' => 111111,
            'account_name' => '義援金口座1',
            'jp_gienkin_id' => 1,
        ];
        foreach ($attrs as $attr => $expect) {
            $this->assertEquals($expect, $model->$attr);
        }

        $gienkin = $model->jpGienkin;
        $this->assertInstanceOf(JpGienkin::class, $gienkin);
        $this->assertEquals('義援金グループ1', $gienkin->name);
    }
}
