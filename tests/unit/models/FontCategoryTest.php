<?php

declare(strict_types=1);

namespace tests\unit\models;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use app\models\Font;
use app\models\FontCategory;

class FontCategoryTest extends Unit
{
    protected UnitTester $tester;

    /** @dataProvider getFontCategoryData */
    public function testFontCategoryData(int $id, string $name): void
    {
        $model = FontCategory::findOne(['id' => $id]);
        $this->assertInstanceOf(FontCategory::class, $model);
        $this->assertEquals($id, $model->id);
        $this->assertEquals($name, $model->name);
    }

    public function testFont(): void
    {
        $model = FontCategory::findOne(['id' => 1]); // 明朝
        $this->assertInstanceOf(FontCategory::class, $model);
        $fonts = $model->fonts;
        $this->assertIsArray($fonts);
        $this->assertGreaterThan(0, count($fonts));

        // ipaexm を含む
        $ipaexmTmp = array_filter($fonts, fn($font) => $font->key === 'ipaexm');
        $this->assertEquals(1, count($ipaexmTmp));
        $ipaexm = array_shift($ipaexmTmp);
        $this->assertInstanceOf(Font::class, $ipaexm);

        // ipaexg を含まない（明朝カテゴリを列挙したので）
        $ipaexgTmp = array_filter($fonts, fn($font) => $font->key === 'ipaexg');
        $this->assertEquals(0, count($ipaexgTmp));
    }

    public function testValidate(): void
    {
        $model = Yii::createObject([
            'class' => FontCategory::class,
            'id' => 0x7fffffff,
            'name' => 'hoge',
            'rank' => 0x7fffffff,
        ]);
        $this->assertTrue($model->validate());

        $model->name = 100; // @phpstan-ignore-line
        $model->rank = null; // @phpstan-ignore-line
        $this->assertFalse($model->validate());
        $this->assertTrue($model->hasErrors('name'));
        $this->assertTrue($model->hasErrors('rank'));
    }

    public function getFontCategoryData(): array
    {
        return [
            '明朝'      => [1, '明朝体'],
            'ゴシック'  => [2, 'ゴシック体'],
            '丸ゴシ'    => [3, '丸ゴシック体'],
            '手書き'    => [4, '手書き風'],
        ];
    }
}
