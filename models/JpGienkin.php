<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use app\models\query\DestPresetQuery;
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
    public function getDestPresets(): DestPresetQuery
    {
        $query = $this->hasMany(DestPreset::class, ['jp_gienkin_id' => 'id']);
        assert($query instanceof DestPresetQuery);
        return $query
            ->valid()
            ->gienkin()
            ->orderBy(['name' => SORT_ASC, 'id' => SORT_ASC]);
    }
}
