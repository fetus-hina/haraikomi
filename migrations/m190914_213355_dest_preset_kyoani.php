<?php
declare(strict_types=1);

use yii\db\Migration;

class m190914_213355_dest_preset_kyoani extends Migration
{
    public function safeUp()
    {
        $this->batchInsert(
            'dest_preset',
            ['name', 'account1', 'account2', 'account3', 'account_name', 'valid_from', 'valid_to'],
            [
                [
                    '京アニ放火 - 日本赤十字社',
                    980,
                    1,
                    323280,
                    '日赤７．１８放火事件被害者義援金',
                    strtotime('2019-09-09T00:00:00+09:00'),
                    strtotime('2019-11-01T00:00:00+09:00'),
                ],
                [
                    '京アニ放火 - 京都府共同募金会',
                    970,
                    5,
                    323289,
                    '共募京都府７．１８放火事件被害者義援金',
                    strtotime('2019-09-09T00:00:00+09:00'),
                    strtotime('2019-11-01T00:00:00+09:00'),
                ],
            ]
        );
    }

    public function safeDown()
    {
        $this->delete('dest_preset', ['and',
            ['like', 'name', '京アニ放火%', false],
        ]);
    }
}
