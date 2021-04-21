<?php

declare(strict_types=1);

namespace tests\unit\models;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use app\models\Font;
use app\models\FontCategory;

class FontTest extends Unit
{
    protected UnitTester $tester;

    /** @dataProvider getFontData */
    public function testFontData(string $key, string $name, bool $isFixed, ?string $relatedFixedFontKey): void
    {
        $model = Font::findOne(['key' => $key]);
        $this->assertInstanceOf(Font::class, $model);
        $this->assertEquals($name, $model->name);
        $this->assertEquals($isFixed, (bool)$model->is_fixed);
        if ($relatedFixedFontKey === null) {
            $this->assertNull($model->fixed);
        } else {
            $this->assertInstanceOf(Font::class, $model->fixed);
            $this->assertEquals($relatedFixedFontKey, $model->fixed->key);
        }
        $this->assertInstanceOf(FontCategory::class, $model->category);
    }

    /** @dataProvider getFixedToProportionalData */
    public function testFixedToProportional(string $fixedKey, string $propKey): void
    {
        $model = Font::findOne(['key' => $fixedKey]);
        $this->assertInstanceOf(Font::class, $model);

        $fonts = $model->fonts;
        $this->assertIsArray($fonts);
        $this->assertGreaterThan(0, count($fonts));

        // $propKey を含む
        $tmp = array_filter($fonts, fn($font) => $font->key === $propKey);
        $this->assertEquals(1, count($tmp));
    }

    public function testValidate(): void
    {
        $model = Yii::createObject([
            'class' => Font::class,
            'id' => 0x7fffffff,
            'category_id' => 1,
            'key' => 'hoge',
            'name' => 'hoge',
            'rank' => 0x7fffffff,
            'is_fixed' => false,
            'fixed_id' => 1,
        ]);
        $this->assertTrue($model->validate());
    }

    public function getFontData(): array
    {
        return [
            'ipaexm' => ['ipaexm',  'IPAex明朝',     false, 'ipam'],
            'ipam'   => ['ipam',    'IPA明朝',       true,  null],
            'ipaexg' => ['ipaexg',  'IPAexゴシック', false, 'ipag'],
            'ipag'   => ['ipag',    'IPAゴシック',   true,  null],
            'M+ 1p'  => ['mplus1p', 'M+ 1p',         false, 'mplus1m'],
            'M+ 1m'  => ['mplus1m', 'M+ 1m',         true,  null],
        ];
    }

    public function getFixedToProportionalData(): array
    {
        return [
            'ipam'  => ['ipam', 'ipaexm'],
            'ipag'  => ['ipag', 'ipaexg'],
            'M+ 1m' => ['mplus1m', 'mplus1p'],
        ];
    }

    public function testQueryHasFixedVariant(): void
    {
        $query = Font::find()->hasFixedVariant();
        $sql = $query->createCommand()->rawSql;

        $db = Yii::$app->db;
        $this->assertTrue(
            str_contains(
                (string)$sql,
                vsprintf(' NOT (%s IS NULL) ', [
                    $db->quoteColumnName('fixed_id'),
                ])
            ) ||
            str_contains(
                (string)$sql,
                vsprintf(' NOT (%s.%s IS NULL) ', [
                    $db->quoteTableName('font'),
                    $db->quoteColumnName('fixed_id'),
                ])
            )
        );
    }
}
