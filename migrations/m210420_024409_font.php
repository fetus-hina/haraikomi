<?php

declare(strict_types=1);

use yii\db\Migration;

final class m210420_024409_font extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%font_category}}', [
            'id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'rank' => $this->integer()->notNull()->unique(),
            'PRIMARY KEY ([[id]])',
        ]);
        $this->batchInsert('{{%font_category}}', ['id', 'name', 'rank'], [
            [1, '明朝体', 10],
            [2, 'ゴシック体', 20],
            [3, '丸ゴシック体', 30],
            [4, '手書き風', 40],
        ]);

        $this->createTable('{{%font}}', [
            'id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'key' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'rank' => $this->integer()->notNull(),
            'is_fixed' => $this->boolean()->notNull(),
            'fixed_id' => $this->integer()->null(),
            'PRIMARY KEY ([[id]])',
            'FOREIGN KEY ([[category_id]]) REFERENCES {{%font_category}} ([[id]])',
            'FOREIGN KEY ([[fixed_id]]) REFERENCES {{%font}} ([[id]])',
        ]);
        $this->batchInsert('{{%font}}', ['id', 'category_id', 'key', 'name', 'rank', 'is_fixed', 'fixed_id'], [
            [ 1, 1, 'ipaexm',         'IPAex明朝',                   10, false, 2],
            [ 2, 1, 'ipam',           'IPA明朝',                     20, true,  null],
            [ 3, 1, 'umepmo3',        '梅P明朝',                     30, false, null],
            [ 4, 2, 'ipaexg',         'IPAexゴシック',               40, false, 5],
            [ 5, 2, 'ipag',           'IPAゴシック',                 50, true,  null],
            [ 6, 2, 'genshingothic',  '源真ゴシック',                60, false, null],
            [ 7, 2, 'umepgo4',        '梅Pゴシック',                 70, false, null],
            [ 8, 2, 'mplus1p',        'M+ 1p',                       80, false, null],
            [ 9, 3, 'genjyuugothic',  '源柔ゴシック',                90, false, null],
            [10, 4, 'nyashi',         'にゃしぃフォント改二／睦月', 100, true,  null],
            [11, 4, 'nyashi_friends', 'にゃしぃフレンズ／如月',     110, true,  null],
        ]);
        return true;
    }

    public function safeDown()
    {
        $this->dropTable('{{%font}}');
        $this->dropTable('{{%font_category}}');
        return true;
    }
}
