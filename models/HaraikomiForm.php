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
            ->setPostalCode($this->postal_code)
            ->setPhone($this->phone1, $this->phone2, $this->phone3);
        return $pdf->render();
    }
}
