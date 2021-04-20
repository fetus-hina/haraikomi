<?php

declare(strict_types=1);

namespace tests\unit\models;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use app\models\Prefecture;

class PrefectureTest extends Unit
{
    protected UnitTester $tester;

    public function testPrefectureDataCount(): void
    {
        $this->assertEquals(47, Prefecture::find()->count());
    }

    /** @dataProvider getPrefectureData */
    public function testPrefectureData(int $id, string $name): void
    {
        $model = Prefecture::findOne(['id' => $id]);
        $this->assertInstanceOf(Prefecture::class, $model);
        $this->assertEquals($id, $model->id);
        $this->assertEquals($name, $model->name);
    }

    public function testValidate(): void
    {
        $model = Yii::createObject([
            'class' => Prefecture::class,
            'id' => 100,
            'name' => 'hoge',
        ]);
        $this->assertTrue($model->validate());

        $model->name = 100; // @phpstan-ignore-line 意図的に違反させるので
        $this->assertFalse($model->validate());
        $this->assertTrue($model->hasErrors('name'));
    }

    public function getPrefectureData(): array
    {
        return [
            '01:北海道' => [1, '北海道'],
            '02:青森県' => [2, '青森県'],
            '03:岩手県' => [3, '岩手県'],
            '04:宮城県' => [4, '宮城県'],
            '05:秋田県' => [5, '秋田県'],
            '06:山形県' => [6, '山形県'],
            '07:福島県' => [7, '福島県'],
            '08:茨城県' => [8, '茨城県'],
            '09:栃木県' => [9, '栃木県'],
            '10:群馬県' => [10, '群馬県'],
            '11:埼玉県' => [11, '埼玉県'],
            '12:千葉県' => [12, '千葉県'],
            '13:東京都' => [13, '東京都'],
            '14:神奈川県' => [14, '神奈川県'],
            '15:新潟県' => [15, '新潟県'],
            '16:富山県' => [16, '富山県'],
            '17:石川県' => [17, '石川県'],
            '18:福井県' => [18, '福井県'],
            '19:山梨県' => [19, '山梨県'],
            '20:長野県' => [20, '長野県'],
            '21:岐阜県' => [21, '岐阜県'],
            '22:静岡県' => [22, '静岡県'],
            '23:愛知県' => [23, '愛知県'],
            '24:三重県' => [24, '三重県'],
            '25:滋賀県' => [25, '滋賀県'],
            '26:京都府' => [26, '京都府'],
            '27:大阪府' => [27, '大阪府'],
            '28:兵庫県' => [28, '兵庫県'],
            '29:奈良県' => [29, '奈良県'],
            '30:和歌山県' => [30, '和歌山県'],
            '31:鳥取県' => [31, '鳥取県'],
            '32:島根県' => [32, '島根県'],
            '33:岡山県' => [33, '岡山県'],
            '34:広島県' => [34, '広島県'],
            '35:山口県' => [35, '山口県'],
            '36:徳島県' => [36, '徳島県'],
            '37:香川県' => [37, '香川県'],
            '38:愛媛県' => [38, '愛媛県'],
            '39:高知県' => [39, '高知県'],
            '40:福岡県' => [40, '福岡県'],
            '41:佐賀県' => [41, '佐賀県'],
            '42:長崎県' => [42, '長崎県'],
            '43:熊本県' => [43, '熊本県'],
            '44:大分県' => [44, '大分県'],
            '45:宮崎県' => [45, '宮崎県'],
            '46:鹿児島県' => [46, '鹿児島県'],
            '47:沖縄県' => [47, '沖縄県'],
        ];
    }
}
