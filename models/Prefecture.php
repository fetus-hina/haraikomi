<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 */
final class Prefecture extends ActiveRecord
{
    public static function find(): ActiveQuery
    {
        return parent::find()
            ->orderBy(['{{prefecture}}.[[id]]' => SORT_ASC]);
    }

    public static function tableName()
    {
        return 'prefecture';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 4],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
}
