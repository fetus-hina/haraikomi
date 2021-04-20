<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\base\Model;

class HaraikomiForm extends Model
{
    /** @var mixed */
    public $account1;
    /** @var mixed */
    public $account2;
    /** @var mixed */
    public $account3;
    /** @var mixed */
    public $amount;
    /** @var mixed */
    public $account_name;
    /** @var mixed */
    public $postal_code;
    /** @var mixed */
    public $pref_id;
    /** @var mixed */
    public $address1;
    /** @var mixed */
    public $address2;
    /** @var mixed */
    public $address3;
    /** @var mixed */
    public $name;
    /** @var mixed */
    public $kana;
    /** @var mixed */
    public $phone1;
    /** @var mixed */
    public $phone2;
    /** @var mixed */
    public $phone3;
    /** @var mixed */
    public $email;
    /** @var mixed */
    public $note;
    /** @var mixed */
    public $font_ja;
    /** @var mixed */
    public $use_fixed;
    /** @var mixed */
    public $draw_form;

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

            [['postal_code'], 'integer'],
            [['postal_code'], 'string', 'min' => 7, 'max' => 7],

            [['pref_id'], 'integer'],
            [['pref_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => Prefecture::class,
                'targetAttribute' => ['pref_id' => 'id'],
            ],

            [['address1', 'address2', 'address3'], 'string'],
            [['name'], 'string', 'min' => 1],
            [['kana'], 'string'],

            [['phone1', 'phone2', 'phone3'], 'integer'],
            [['phone1'], 'string', 'min' => 2, 'max' => 5],
            [['phone2'], 'string', 'min' => 1, 'max' => 4],
            [['phone3'], 'string', 'min' => 4, 'max' => 4],

            [['email'], 'string'],
            [['email'], 'email',
                'allowName' => false,
                'checkDNS' => true,
                'enableIDN' => false,
            ],

            [['note'], 'string'],

            [['font_ja'], 'required'],
            [['font_ja'], 'string'],
            [['font_ja'], 'in', 'range' => array_keys($this->getJapaneseFonts())],
            [['use_fixed'], 'boolean'],

            [['draw_form'], 'boolean'],
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
            'email' => 'メールアドレス（任意）',
            'note' => '通信欄',
            'font_ja' => '日本語フォント',
            'use_fixed' => '通信欄への等幅フォントの利用',
            'draw_form' => '罫線等を描画する',
        ];
    }

    public function makePdf(): string
    {
        $fontNameHumanReadable = ($this->getJapaneseFonts()[$this->font_ja] ?? '');
        $pdf = Yii::createObject([
                'class' => Pdf::class,
                'fontNameJa' => $this->font_ja,
                'fontNameNote' => ($this->use_fixed)
                    ? ($this->getFixedWidthFont($this->font_ja) ?? $this->font_ja)
                    : $this->font_ja,
                'normalizeToWide' => strpos($fontNameHumanReadable, '明朝') !== false,
                'drawLines' => !!$this->draw_form,
            ])
            ->setAccount($this->account1, $this->account2, $this->account3)
            ->setAccountName($this->account_name)
            ->setAmount($this->amount)
            ->setNote($this->note)
            ->setAddress(
                $this->postal_code,
                Prefecture::findOne(['id' => $this->pref_id])->name ?? '',
                $this->address1,
                $this->address2,
                $this->address3,
                $this->name,
                $this->kana,
                $this->phone1,
                $this->phone2,
                $this->phone3,
                $this->email,
            );
        return $pdf->render();
    }

    public function getJapaneseFonts(): array
    {
        $fonts = [];
        foreach (Font::find()->all() as $font) {
            $fonts[$font->key] = vsprintf('%s（%s）%s', [
                $font->category->name,
                $font->name,
                $font->is_fixed ? '【等幅】' : '',
            ]);
        }
        return $fonts;
    }

    public function getFixedWidthFont(string $fontId): ?string
    {
        $orgFont = Font::find()->andWhere(['key' => $fontId])->one();
        if ($orgFont) {
            $fwFont = $orgFont->fixed;
            if ($fwFont) {
                return $fwFont->key;
            }
        }
        return null;
    }
}
