<?php

declare(strict_types=1);

use yii\db\Migration;

class m190701_093615_dest_preset extends Migration
{
    public function safeUp()
    {
        $this->createTable('dest_preset', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'account1' => $this->integer()->notNull(),
            'account2' => $this->integer()->notNull(),
            'account3' => $this->integer()->notNull(),
            'account_name' => $this->string(64)->notNull(),
            'valid_from' => $this->integer()->notNull(),
            'valid_to' => $this->integer()->notNull(),
        ]);
        $this->batchInsert(
            'dest_preset',
            ['name', 'account1', 'account2', 'account3', 'account_name', 'valid_from', 'valid_to'],
            [
                [
                    '東日本大震災 - 日本赤十字社',
                    140,
                    8,
                    507,
                    '日本赤十字社　東日本大震災義援金',
                    strtotime('2011-03-14T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '東日本大震災 - 政府窓口',
                    130,
                    6,
                    623461,
                    '東日本大震災義援金政府窓口',
                    strtotime('2011-04-05T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '東日本大震災 - 岩手県',
                    100,
                    2,
                    552,
                    '岩手県災害義援金募集委員会',
                    strtotime('2011-03-15T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '東日本大震災 - 宮城県',
                    170,
                    0,
                    526,
                    '宮城県災害対策本部',
                    strtotime('2011-03-14T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '東日本大震災 - 宮城県東松島市',
                    110,
                    1,
                    650,
                    '東松島市災害対策本部',
                    strtotime('2011-03-24T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '東日本大震災 - 宮城県石巻市',
                    180,
                    7,
                    770,
                    '石巻市災害対策本部',
                    strtotime('2011-11-01T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '東日本大震災 - 宮城県女川町',
                    140,
                    3,
                    687,
                    '女川町災害対策本部',
                    strtotime('2011-04-01T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '東日本大震災 - 宮城県七ヶ浜町',
                    2200,
                    6,
                    123,
                    '七ヶ浜町災害義援金',
                    strtotime('2012-07-26T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '東日本大震災 - 福島県',
                    160,
                    3,
                    533,
                    '福島県災害対策本部',
                    strtotime('2011-03-15T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '東日本大震災 - 福島県広野町',
                    150,
                    8,
                    710,
                    '広野町災害対策本部',
                    strtotime('2011-04-01T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '東日本大震災 - 茨城県',
                    140,
                    2,
                    638,
                    '茨城県災害対策本部',
                    strtotime('2011-03-24T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '東日本大震災 - 茨城県高萩市',
                    150,
                    1,
                    736,
                    '高萩市災害義援金',
                    strtotime('2011-04-05T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '熊本地震 - 日本赤十字社',
                    130,
                    4,
                    265072,
                    '日赤平成28年熊本地震災害義援金',
                    strtotime('2016-04-18T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '熊本地震 - 熊本県',
                    940,
                    0,
                    174320,
                    '熊本地震義援金',
                    strtotime('2016-04-19T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '熊本地震 - 熊本県共同募金',
                    950,
                    2,
                    174321,
                    '熊本県共同募金会熊本地震義援金',
                    strtotime('2016-04-19T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '熊本地震 - 熊本市',
                    960,
                    3,
                    174322,
                    '熊本市熊本地震災害義援金',
                    strtotime('2016-04-19T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '熊本地震 - 宇土市',
                    970,
                    5,
                    174323,
                    '宇土市災害義援金',
                    strtotime('2016-04-21T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '熊本地震 - 阿蘇市',
                    970,
                    9,
                    209736,
                    '阿蘇市熊本地震義捐金',
                    strtotime('2016-04-21T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '熊本地震 - 南阿蘇村',
                    980,
                    1,
                    209737,
                    '南阿蘇村災害義援金',
                    strtotime('2016-04-21T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '熊本地震 - 益城町',
                    960,
                    5,
                    235897,
                    '益城町災害対策本部',
                    strtotime('2016-04-21T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '熊本地震 - 政府窓口',
                    170,
                    6,
                    292463,
                    '平成２８年熊本地震被災者義援金政府窓口',
                    strtotime('2016-04-22T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '熊本地震 - 菊池市',
                    990,
                    2,
                    209738,
                    '菊池市災害義援金',
                    strtotime('2016-04-25T00:00:00+09:00'),
                    strtotime('2020-04-01T00:00:00+09:00'),
                ],
                [
                    '2018年7月豪雨 - 日本赤十字社',
                    130,
                    8,
                    635289,
                    '日赤平成３０年７月豪雨災害義援金',
                    strtotime('2018-07-10T00:00:00+09:00'),
                    strtotime('2020-07-01T00:00:00+09:00'),
                ],
                [
                    '北海道地震 - 日本赤十字社',
                    130,
                    1,
                    673591,
                    '日赤平成３０年北海道胆振東部地震災害義援金',
                    strtotime('2018-09-11T00:00:00+09:00'),
                    strtotime('2019-10-01T00:00:00+09:00'),
                ],
            ]
        );
        return true;
    }

    public function safeDown()
    {
        $this->dropTable('dest_preset');
        return true;
    }
}
