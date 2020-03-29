<?php

declare(strict_types=1);

use yii\db\Migration;

class m200329_091457_comiket extends Migration
{
    public function safeUp()
    {
        if (!$data = $this->getData()) {
            return false;
        }

        $this->batchInsert(
            'dest_preset',
            array_merge(array_keys($data[0]), ['valid_from', 'valid_to']),
            array_map(
                function (array $row): array {
                    return array_merge(array_values($row), [0, '9223372036854775807']);
                },
                $data
            )
        );
    }

    public function safeDown()
    {
        if (!$data = $this->getData()) {
            return false;
        }

        foreach ($data as $row) {
            $this->delete('dest_preset', $row);
        }
    }

    public function getData(): array
    {
        return [
            [
                'name' => 'コミケ - 駐車券',
                'account1' => 110,
                'account2' => 0,
                'account3' => 777495,
                'account_name' => 'コミックマーケットＰ係',
            ],
            [
                'name' => 'コミケ - カタログ通販A',
                'account1' => 140,
                'account2' => 4,
                'account3' => 594964,
                'account_name' => 'コミケットカタログ通販Ａ係',
            ],
            [
                'name' => 'コミケ - カタログ通販B',
                'account1' => 190,
                'account2' => 8,
                'account3' => 631962,
                'account_name' => 'コミケットカタログ通販Ｂ係',
            ],
            [
                'name' => 'コミケ - DVDカタログ',
                'account1' => 180,
                'account2' => 5,
                'account3' => 280833,
                'account_name' => '株式会社クリエイション',
            ],
        ];
    }
}
