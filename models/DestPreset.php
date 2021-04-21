<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use app\models\query\DestPresetQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dest_preset".
 *
 * @property int $id
 * @property string $name
 * @property int $account1
 * @property int $account2
 * @property int $account3
 * @property string $account_name
 * @property int $valid_from
 * @property int $valid_to
 * @property int|null $jp_gienkin_id
 *
 * @property ?JpGienkin $jpGienkin
 */
class DestPreset extends ActiveRecord
{
    public static function find(): DestPresetQuery
    {
        return Yii::createObject(DestPresetQuery::class, [static::class]);
    }

    public static function tableName(): string
    {
        return 'dest_preset';
    }

    public function rules()
    {
        return [
            [['name', 'account1', 'account2', 'account3', 'account_name', 'valid_from', 'valid_to'], 'required'],
            [['account1', 'account2', 'account3', 'valid_from', 'valid_to', 'jp_gienkin_id'], 'integer'],
            [['name', 'account_name'], 'string', 'max' => 64],
            [['jp_gienkin_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => JpGienkin::class,
                'targetAttribute' => ['jp_gienkin_id' => 'id'],
            ],
        ];
    }

    /** @codeCoverageIgnore */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'account1' => 'Account1',
            'account2' => 'Account2',
            'account3' => 'Account3',
            'account_name' => 'Account Name',
            'valid_from' => 'Valid From',
            'valid_to' => 'Valid To',
            'jp_gienkin_id' => 'Jp Gienkin ID',
        ];
    }

    /**
     * Gets query for [[JpGienkin]].
     */
    public function getJpGienkin(): ActiveQuery
    {
        return $this->hasOne(JpGienkin::class, ['id' => 'jp_gienkin_id']);
    }
}
