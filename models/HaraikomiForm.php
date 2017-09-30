<?php
namespace app\models;

use Yii;
use yii\base\Model;

class HaraikomiForm extends Model
{
    public $account1;
    public $account2;
    public $account3;
    public $amount;
    public $account_name;
    public $postal_code;
    public $pref_id;
    public $address1;
    public $address2;
    public $address3;
    public $name;
    public $kana;
    public $phone1;
    public $phone2;
    public $phone3;

    public function rules()
    {
        return [
            [['account1', 'account2', 'account3'], 'required'],
            [['account1', 'account2', 'account3'], 'integer'],
            [['account1'], 'string', 'min' => 5, 'max' => 5],
            [['account2'], 'string', 'min' => 1, 'max' => 1],
            [['account3'], 'string', 'min' => 1, 'max' => 7],

            [['amount'], 'required'],
            [['amount'], 'integer', 'min' => 1],
            [['amount'], 'string', 'min' => 1, 'max' => 8],

            [['account_name'], 'required'],
            [['account_name'], 'string', 'min' => 1],

            [['postal_code'], 'required'],
            [['postal_code'], 'integer'],
            [['postal_code'], 'string', 'min' => 7, 'max' => 7],

            [['pref_id'], 'required'],
            [['pref_id'], 'integer', 'min' => 1, 'max' => 47],

            [['address1', 'address2'], 'required'],
            [['address1', 'address2', 'address3'], 'string'],
            [['name'], 'required'],
            [['name'], 'string', 'min' => 1],
            [['kana'], 'string'],

            [['phone1', 'phone2', 'phone3'], 'required'],
            [['phone1', 'phone2', 'phone3'], 'integer'],
            [['phone1'], 'string', 'min' => 2, 'max' => 5],
            [['phone2'], 'string', 'min' => 1, 'max' => 4],
            [['phone3'], 'string', 'min' => 4, 'max' => 4],
        ];
    }

    public function attributeLabels()
    {
        return [
            'account1' => '記号(1)',
            'account2' => '記号(2)',
            'account3' => '番号',
            'amount' => '払込金額',
            'account_name' => '加入者名',
            'postal_code' => '依頼人郵便番号',
            'pref_id' => '依頼人都道府県',
            'address1' => '依頼人住所（市区町村）',
            'address2' => '依頼人住所（番地等）',
            'address3' => '依頼人住所（建物名部屋番号等）',
            'name' => '依頼人氏名',
            'kana' => 'カナ（任意）',
            'phone1' => '電話番号(1)',
            'phone2' => '電話番号(2)',
            'phone3' => '電話番号(3)',
        ];
    }

    public function makePdf() : string
    {
        $pdf = Yii::createObject(Pdf::class)
            ->setAccount($this->account1, $this->account2, $this->account3)
            ->setAccountName($this->account_name)
            ->setAmount($this->amount)
            ->setAddress(
                $this->postal_code,
                $this->getPrefList()[$this->pref_id],
                $this->address1,
                $this->address2,
                $this->address3,
                $this->name,
                $this->kana,
                $this->phone1,
                $this->phone2,
                $this->phone3
            );
        return $pdf->render();
    }

    public function getPrefList() : array
    {
        return [
             1 => '北海道',
             2 => '青森県',
             3 => '岩手県',
             4 => '宮城県',
             5 => '秋田県',
             6 => '山形県',
             7 => '福島県',
             8 => '茨城県',
             9 => '栃木県',
            10 => '群馬県',
            11 => '埼玉県',
            12 => '千葉県',
            13 => '東京都',
            14 => '神奈川県',
            15 => '新潟県',
            16 => '富山県',
            17 => '石川県',
            18 => '福井県',
            19 => '山梨県',
            20 => '長野県',
            21 => '岐阜県',
            22 => '静岡県',
            23 => '愛知県',
            24 => '三重県',
            25 => '滋賀県',
            26 => '京都府',
            27 => '大阪府',
            28 => '兵庫県',
            29 => '奈良県',
            30 => '和歌山県',
            31 => '鳥取県',
            32 => '島根県',
            33 => '岡山県',
            34 => '広島県',
            35 => '山口県',
            36 => '徳島県',
            37 => '香川県',
            38 => '愛媛県',
            39 => '高知県',
            40 => '福岡県',
            41 => '佐賀県',
            42 => '長崎県',
            43 => '熊本県',
            44 => '大分県',
            45 => '宮崎県',
            46 => '鹿児島県',
            47 => '沖縄県',
        ];
    }
}
