<?php

declare(strict_types=1);

use yii\db\Migration;
use yii\db\Query;

final class m220409_012256_biz_ud_fonts extends Migration
{
    private const RANK_BIZ_UD_PMINCHO = 25; // IPA明朝の後、梅P明朝の前
    private const RANK_BIZ_UD_MINCHO = 26;
    private const RANK_BIZ_UD_PGOTHIC = 55; // IPAゴシックの後、源真ゴシックの前
    private const RANK_BIZ_UD_GOTHIC = 56;

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $data = $this->getData();
        $this->batchInsert(
            '{{%font}}',
            array_keys($data[0]),
            array_map(
                fn (array $item): array => array_values($item),
                $data,
            ),
        );
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('{{%font}}', [
            'key' => array_map(
                fn (array $row): string => $row['key'],
                $this->getData(),
            ),
        ]);

        return true;
    }

    private function getData(): array
    {
        $startId = $this->getStartId();
        $catIdGothic = $this->getGothicCategoryId();
        $catIdMincho = $this->getMinchoCategoryId();

        return [
            [
                'id' => $startId + 1,
                'category_id' => $catIdMincho,
                'key' => 'bizudmincho',
                'name' => 'BIZ UD明朝',
                'rank' => self::RANK_BIZ_UD_MINCHO,
                'is_fixed' => true,
                'fixed_id' => null,
            ],
            [
                'id' => $startId + 0,
                'category_id' => $catIdMincho,
                'key' => 'bizudpmincho',
                'name' => 'BIZ UDP明朝',
                'rank' => self::RANK_BIZ_UD_PMINCHO,
                'is_fixed' => false,
                'fixed_id' => $startId + 1,
            ],
            [
                'id' => $startId + 3,
                'category_id' => $catIdGothic,
                'key' => 'bizudgothic',
                'name' => 'BIZ UDゴシック',
                'rank' => self::RANK_BIZ_UD_GOTHIC,
                'is_fixed' => true,
                'fixed_id' => null,
            ],
            [
                'id' => $startId + 2,
                'category_id' => $catIdGothic,
                'key' => 'bizudpgothic',
                'name' => 'BIZ UDPゴシック',
                'rank' => self::RANK_BIZ_UD_PGOTHIC,
                'is_fixed' => false,
                'fixed_id' => $startId + 3,
            ],
        ];
    }

    private function getGothicCategoryId(): int
    {
        return $this->getCategoryId('ゴシック体');
    }

    private function getMinchoCategoryId(): int
    {
        return $this->getCategoryId('明朝体');
    }

    private function getCategoryId(string $name): int
    {
        $query = (new Query())
            ->select(['id'])
            ->from('{{%font_category}}')
            ->andWhere(['name' => $name])
            ->limit(1);
        $value = filter_var($query->scalar(), FILTER_VALIDATE_INT);
        return is_int($value) ? $value : throw new RuntimeException();
    }

    private function getStartId(): int
    {
        $query = (new Query())
            ->select(['id' => 'MAX([[id]])'])
            ->from('{{%font}}');
        $value = filter_var($query->scalar(), FILTER_VALIDATE_INT);
        return is_int($value) ? $value + 1 : throw new RuntimeException();
    }
}
