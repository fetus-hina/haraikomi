<?php

declare(strict_types=1);

use yii\db\Migration;

final class m200709_183038_delete_gienkin_data extends Migration
{
    public function safeUp()
    {
        $db = $this->getDb();

        $account = function (int $a1, int $a2, int $a3) use ($db): string {
            return vsprintf('((%1$s = %4$s) AND (%2$s = %5$s) AND (%3$s = %6$s))', [
                $db->quoteColumnName('account1'),
                $db->quoteColumnName('account2'),
                $db->quoteColumnName('account3'),
                $db->quoteValue($a1),
                $db->quoteValue($a2),
                $db->quoteValue($a3),
            ]);
        };

        $sql = vsprintf('DELETE FROM %s WHERE NOT ((%s))', [
            $db->quoteTableName('dest_preset'),
            implode(') OR (', [
                $account(110, 0, 777495),
                $account(140, 4, 594964),
                $account(190, 8, 631962),
                $account(180, 5, 280833),
            ]),
        ]);

        $this->execute($sql);

        return true;
    }

    public function safeDown()
    {
        return false;
    }
}
