<?php

declare(strict_types=1);

namespace app\models;

use yii\base\Model;

final class PostalCodeApiForm extends Model
{
    public ?string $code = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['code'], 'string',
                'skipOnError' => true,
                'min' => 7,
                'max' => 7,
            ],
            [['code'], 'match',
                'skipOnError' => true,
                'pattern' => '/\A[0-9]{7}\z/',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => '郵便番号',
        ];
    }
}
