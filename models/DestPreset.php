<?php
declare(strict_types=1);

namespace app\models;

use Yii;
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
 */
class DestPreset extends ActiveRecord
{
    public static function find(): ActiveQuery
    {
        return new class(static::class) extends ActiveQuery {
            public function valid(): self
            {
                $t = (int)($_SERVER['REQUEST_TIME'] ?? time());
                $this->andWhere(['and',
                    ['<=', 'valid_from', $t],
                    ['>', 'valid_to', $t],
                ]);
                return $this;
            }
        };
    }

    public static function tableName(): string
    {
        return 'dest_preset';
    }

    public function rules()
    {
        return [
            [['name', 'account1', 'account2', 'account3', 'account_name'], 'required'],
            [['valid_from', 'valid_to'], 'required'],
            [['account1', 'account2', 'account3', 'valid_from', 'valid_to'], 'integer'],
            [['name', 'account_name'], 'string', 'max' => 64],
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
            'account1' => 'Account1',
            'account2' => 'Account2',
            'account3' => 'Account3',
            'account_name' => 'Account Name',
            'valid_from' => 'Valid From',
            'valid_to' => 'Valid To',
        ];
    }
}
