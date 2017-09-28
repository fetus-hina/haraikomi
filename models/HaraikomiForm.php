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
        ];
    }

    public function attributeLabels()
    {
        return [
            'account1' => '記号(1)',
            'account2' => '記号(2)',
            'account3' => '番号',
            'amount' => '払込金額',
        ];
    }

    public function makePdf() : string
    {
        $pdf = Yii::createObject(Pdf::class)
            ->setAccount($this->account1, $this->account2, $this->account3)
            ->setAmount($this->amount);
        return $pdf->render();
    }
}
