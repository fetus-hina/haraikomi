<?php

declare(strict_types=1);

use yii\db\Migration;

class m200709_181322_add_jp_gienkin_ref extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            'dest_preset',
            'jp_gienkin_id',
            $this->integer()->null()->append('REFERENCES {{jp_gienkin}}([[id]])')
        );
    }

    public function safeDown()
    {
        return false;
    }
}
