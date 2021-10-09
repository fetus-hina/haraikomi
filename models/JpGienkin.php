<?php

declare(strict_types=1);

namespace app\models;

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
final class JpGienkin extends ActiveRecord
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

    /** @codeCoverageIgnore */
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
     */
    public function getDestPresets(): ActiveQuery
    {
        $t = (int)$_SERVER['REQUEST_TIME'];
        return $this->hasMany(DestPreset::class, ['jp_gienkin_id' => 'id'])
            ->andWhere(['and',
                ['<=', 'valid_from', $t],
                ['>', 'valid_to', $t],
                ['not', ['jp_gienkin_id' => null]],
            ])
            ->orderBy(['name' => SORT_ASC, 'id' => SORT_ASC]);
    }
}
