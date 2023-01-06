<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

use const SORT_ASC;

/**
 * This is the model class for table "font".
 *
 * @property int $id
 * @property int $category_id
 * @property string $key
 * @property string $name
 * @property int $rank
 * @property bool $is_fixed
 * @property int|null $fixed_id
 *
 * @property FontCategory $category
 * @property ?Font $fixed
 * @property Font[] $fonts
 */
final class Font extends ActiveRecord
{
    public static function find(): ActiveQuery
    {
        return parent::find()
            ->innerJoinWith('category')
            ->orderBy([
                FontCategory::tableName() . '.[[rank]]' => SORT_ASC,
                self::tableName() . '.[[rank]]' => SORT_ASC,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'font';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'key', 'name', 'rank', 'is_fixed'], 'required'],
            [['category_id', 'rank', 'fixed_id'], 'integer'],
            [['is_fixed'], 'boolean'],
            [['key', 'name'], 'string', 'max' => 255],
            [['key'], 'unique'],
            [['fixed_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => self::class,
                'targetAttribute' => ['fixed_id' => 'id'],
            ],
            [['category_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => FontCategory::class,
                'targetAttribute' => ['category_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'key' => 'Key',
            'name' => 'Name',
            'rank' => 'Rank',
            'is_fixed' => 'Is Fixed',
            'fixed_id' => 'Fixed ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(FontCategory::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Fixed]].
     */
    public function getFixed(): ActiveQuery
    {
        return $this->hasOne(self::class, ['id' => 'fixed_id']);
    }

    /**
     * Gets query for [[Fonts]].
     */
    public function getFonts(): ActiveQuery
    {
        return $this->hasMany(self::class, ['fixed_id' => 'id']);
    }
}
