<?php

declare(strict_types=1);

use yii\db\Migration;

class m200709_180922_create_jp_gienkin_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%jp_gienkin}}', [
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull()->unique(),
            'ref_time' => $this->integer()->notNull(),
        ]);
        return true;
    }

    public function safeDown()
    {
        $this->dropTable('{{%jp_gienkin}}');
        return true;
    }
}
