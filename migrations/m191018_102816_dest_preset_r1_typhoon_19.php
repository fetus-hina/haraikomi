<?php

declare(strict_types=1);

use yii\db\Migration;

class m191018_102816_dest_preset_r1_typhoon_19 extends Migration
{
    public function safeUp()
    {
        $this->batchInsert(
            'dest_preset',
            ['name', 'account1', 'account2', 'account3', 'account_name', 'valid_from', 'valid_to'],
            [
                [
                    '令和元年台風19号 - 日本赤十字社',
                    190,
                    8,
                    515005,
                    '日赤令和元年台風第１９号災害義援金',
                    strtotime('2019-10-16T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 中央共同募金会',
                    130,
                    0,
                    421020,
                    '中央共同募金会令和元年台風第１９号災害義援金',
                    strtotime('2019-10-16T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 長野県共募',
                    160,
                    2,
                    265830,
                    '長野県共同募金会台風１９号災害義援金',
                    strtotime('2019-10-17T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 栃木県共募',
                    170,
                    7,
                    792207,
                    '栃木県共募令和元年台風第１９号災害義援金',
                    strtotime('2019-10-17T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 埼玉県共募',
                    170,
                    6,
                    265864,
                    '埼玉県共募令和元年台風第１９号災害義援金',
                    strtotime('2019-10-18T00:00:00+09:00'),
                    strtotime('2019-11-30T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 静岡県共募',
                    120,
                    6,
                    363779,
                    '静岡県共同募金会台風第１９号災害義援金',
                    strtotime('2019-10-18T00:00:00+09:00'),
                    strtotime('2019-12-28T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 宮城県',
                    180,
                    2,
                    731251,
                    '宮城県災害対策本部',
                    strtotime('2019-10-18T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 福島県',
                    150,
                    5,
                    792173,
                    '福島県災害対策本部',
                    strtotime('2019-10-17T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 栃木県',
                    180,
                    4,
                    487570,
                    '令和元年台風第１９号栃木県災害義援金',
                    strtotime('2019-10-17T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 長野県',
                    170,
                    0,
                    324895,
                    '長野県台風第１９号災害対策本部',
                    strtotime('2019-10-17T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 愛媛県民義援金',
                    930,
                    0,
                    237591,
                    '令和元年台風第１９号愛媛県民義援金',
                    strtotime('2019-10-18T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 糸魚川市',
                    190,
                    2,
                    324929,
                    '糸魚川市災害義援金',
                    strtotime('2019-10-18T00:00:00+09:00'),
                    strtotime('2019-12-31T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 栃木市',
                    140,
                    4,
                    421054,
                    '栃木市災害対策本部',
                    strtotime('2019-10-18T00:00:00+09:00'),
                    strtotime('2020-02-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 鹿沼市',
                    150,
                    9,
                    767954,
                    '鹿沼市令和元年台風１９号災害義援金',
                    strtotime('2019-10-17T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 中野市',
                    190,
                    0,
                    392536,
                    '令和元年台風１９号中野市災害義援金',
                    strtotime('2019-10-17T00:00:00+09:00'),
                    strtotime('2020-10-17T00:00:00+09:00'),
                ],
                [
                    '令和元年台風19号 - 妙高市',
                    110,
                    3,
                    451615,
                    '妙高市災害対策本部',
                    strtotime('2019-10-17T00:00:00+09:00'),
                    strtotime('2019-12-31T00:00:00+09:00'),
                ],
            ]
        );
        return true;
    }

    public function safeDown()
    {
        $this->delete('dest_preset', ['and',
            ['like', 'name', '令和元年台風19号%', false],
        ]);
        return true;
    }
}
