<?php

declare(strict_types=1);

use yii\db\Migration;

final class m200709_181322_add_jp_gienkin_ref extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            'dest_preset',
            'jp_gienkin_id',
            (string)$this->integer()->null()->append('REFERENCES {{jp_gienkin}}([[id]])')
        );
        return true;
    }

    public function safeDown()
    {
        return false;
    }
}
