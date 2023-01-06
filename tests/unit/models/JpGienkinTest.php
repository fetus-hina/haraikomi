<?php

declare(strict_types=1);

namespace tests\unit\models;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use app\models\DestPreset;
use app\models\JpGienkin;
use app\tests\fixtures\DestPresetFixture;
use app\tests\fixtures\JpGienkinFixture;

use function array_map;
use function array_reduce;
use function count;
use function gmmktime;

final class JpGienkinTest extends Unit
{
    protected UnitTester $tester;

    public function testValidate(): void
    {
        $model = Yii::createObject([
            'class' => JpGienkin::class,
            'name' => 'hoge',
            'ref_time' => gmmktime(9, 0, 0, 1, 1, 2000),
        ]);
        $this->assertTrue($model->validate());
    }

    public function testGetDestPresets(): void
    {
        $this->tester->haveFixtures([
            DestPresetFixture::class,
            JpGienkinFixture::class,
        ]);

        $model = JpGienkin::findOne(['id' => 1]);
        $this->assertInstanceOf(JpGienkin::class, $model);

        $presets = $model->destPresets;
        $this->assertIsArray($presets);
        $this->assertEquals(2, count($presets));

        // 全ての要素が DestPreset のインスタンスであることを確認
        $this->assertTrue(array_reduce(
            array_map(
                fn ($item): bool => ($item instanceof DestPreset),
                $presets,
            ),
            fn (bool $acc, bool $cur): bool => ($acc && $cur),
            true,
        ));

        // 全ての要素の jp_gienkin_id が正しい事を確認
        $this->assertTrue(array_reduce(
            array_map(
                fn (DestPreset $item): bool => ($item->jp_gienkin_id === $model->id),
                $presets,
            ),
            fn (bool $acc, bool $cur): bool => ($acc && $cur),
            true,
        ));

        $this->assertEquals(1, $presets[0]->id);
        $this->assertEquals(2, $presets[1]->id);
    }
}
