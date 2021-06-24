<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\base\Model;

final class PostalCodeApiForm extends Model
{
    public ?string $code = null;

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

    public function attributeLabels()
    {
        return [
            'code' => '郵便番号',
        ];
    }
}
