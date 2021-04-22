<?php

declare(strict_types=1);

use yii\db\Migration;

final class m190919_124316_dest_preset_r1_typhoon_15 extends Migration
{
    public function safeUp()
    {
        $this->batchInsert(
            'dest_preset',
            ['name', 'account1', 'account2', 'account3', 'account_name', 'valid_from', 'valid_to'],
            [
                [
                    '令和元年台風15号 - 日本赤十字社',
                    100,
                    8,
                    451648,
                    '日赤令和元年台風第１５号千葉県災害義援金',
                    strtotime('2019-09-18T00:00:00+09:00'),
                    strtotime('2020-01-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風15号 - 千葉県共募',
                    160,
                    2,
                    293218,
                    '千葉県共募令和元年台風第１５号千葉県災害義援金',
                    strtotime('2019-09-18T00:00:00+09:00'),
                    strtotime('2020-01-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風15号 - 千葉県',
                    160,
                    4,
                    767914,
                    '千葉県災害対策本部',
                    strtotime('2019-09-18T00:00:00+09:00'),
                    strtotime('2020-01-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風15号 - 鋸南町',
                    190,
                    3,
                    791836,
                    '鋸南町災害義援金',
                    strtotime('2019-09-17T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
            ]
        );

        return true;
    }

    public function safeDown()
    {
        $this->delete('dest_preset', ['and',
            ['like', 'name', '令和元年台風15号%', false],
        ]);

        return true;
    }
}
