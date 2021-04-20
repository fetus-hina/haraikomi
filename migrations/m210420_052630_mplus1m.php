<?php

declare(strict_types=1);

use yii\db\Migration;

class m210420_052630_mplus1m extends Migration
{
    public function safeUp()
    {
        $this->insert('{{%font}}', [
            'id' => 12,
            'category_id' => 2,
            'key' => 'mplus1m',
            'name' => 'M+ 1m',
            'rank' => 85,
            'is_fixed' => true,
            'fixed_id' => null,
        ]);
        $this->update('{{%font}}', ['fixed_id' => 12], ['key' => 'mplus1p']);
        return true;
    }

    public function safeDown()
    {
        $this->update('{{%font}}', ['fixed_id' => null], ['key' => 'mplus1p']);
        $this->delete('{{%font}}', ['key' => 'mplus1m']);
        return true;
    }
}
