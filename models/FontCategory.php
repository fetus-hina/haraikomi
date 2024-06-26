<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

use const SORT_ASC;

/**
 * This is the model class for table "font_category".
 *
 * @property int $id
 * @property string $name
 * @property int $rank
 *
 * @property Font[] $fonts
 */
final class FontCategory extends ActiveRecord
{
    public static function find(): ActiveQuery
    {
        return parent::find()
            ->orderBy([self::tableName() . '.[[rank]]' => SORT_ASC]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'font_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'rank'], 'required'],
            [['rank'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['rank'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'rank' => 'Rank',
        ];
    }

    /**
     * Gets query for [[Fonts]].
     */
    public function getFonts(): ActiveQuery
    {
        return $this->hasMany(Font::class, ['category_id' => 'id']);
    }
}
