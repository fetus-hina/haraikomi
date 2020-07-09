<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "jp_gienkin".
 *
 * @property int $id
 * @property string $name
 * @property int $ref_time
 *
 * @property DestPreset[] $destPresets
 */
class JpGienkin extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'jp_gienkin';
    }

    public function rules()
    {
        return [
            [['name', 'ref_time'], 'required'],
            [['name'], 'string'],
            [['ref_time'], 'integer'],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'ref_time' => 'Ref Time',
        ];
    }

    /**
     * Gets query for [[DestPresets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDestPresets(): ActiveQuery
    {
        return $this->hasMany(DestPreset::class, ['jp_gienkin_id' => 'id'])
            ->valid()
            ->gienkin()
            ->orderBy(['name' => SORT_ASC, 'id' => SORT_ASC]);
    }
}
