<?php

declare(strict_types=1);

use yii\db\Migration;

final class m190914_214828_dest_preset_r1_aug_heavy_rain extends Migration
{
    public function safeUp()
    {
        $this->batchInsert(
            'dest_preset',
            ['name', 'account1', 'account2', 'account3', 'account_name', 'valid_from', 'valid_to'],
            [
                [
                    '令和元年8月豪雨 - 日本赤十字社',
                    120,
                    7,
                    696975,
                    '日赤令和元年8月豪雨災害義援金',
                    strtotime('2019-09-02T00:00:00+09:00'),
                    strtotime('2020-03-01T00:00:00+09:00'),
                ],
                [
                    '令和元年8月豪雨 - 佐賀県共募',
                    950,
                    9,
                    237585,
                    '佐賀県共募　令和元年8月佐賀県豪雨災害義援金',
                    strtotime('2019-09-02T00:00:00+09:00'),
                    strtotime('2020-03-01T00:00:00+09:00'),
                ],
                [
                    '令和元年8月豪雨 - 武雄市',
                    920,
                    2,
                    277185,
                    '武雄市災害義援金',
                    strtotime('2019-09-03T00:00:00+09:00'),
                    strtotime('2020-09-01T00:00:00+09:00'),
                ],
                [
                    '令和元年8月豪雨 - 大町町災害義援金',
                    990,
                    4,
                    333727,
                    '大町町災害義援金',
                    strtotime('2019-09-11T00:00:00+09:00'),
                    strtotime('2020-09-01T00:00:00+09:00'),
                ],
            ]
        );
        return true;
    }

    public function safeDown()
    {
        $this->delete('dest_preset', ['and',
            ['like', 'name', '令和元年8月豪雨%', false],
        ]);

        return true;
    }
}
