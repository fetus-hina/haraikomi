<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

final class HaraikomiForm extends Model
{
    public string|null $account1 = null;
    public string|null $account2 = null;
    public string|null $account3 = null;
    public string|null $amount = null;
    public string|null $account_name = null;
    public string|null $postal_code = null;
    public string|int|null $pref_id = null;
    public string|null $address1 = null;
    public string|null $address2 = null;
    public string|null $address3 = null;
    public string|null $name = null;
    public string|null $kana = null;
    public string|null $phone1 = null;
    public string|null $phone2 = null;
    public string|null $phone3 = null;
    public string|null $email = null;
    public string|null $note = null;
    public string|null $font_ja = null;
    public string|int|bool|null $use_fixed = null;
    public string|int|bool|null $draw_form = null;

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

    /** @codeCoverageIgnore */
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
            ->setAccount(
                (string)$this->account1,
                (string)$this->account2,
                (string)$this->account3,
            )
            ->setAccountName((string)$this->account_name)
            ->setAmount((string)$this->amount)
            ->setNote($this->note)
            ->setAddress(
                (string)$this->postal_code,
                Prefecture::findOne(['id' => $this->pref_id])->name ?? '',
                (string)$this->address1,
                (string)$this->address2,
                $this->address3,
                (string)$this->name,
                $this->kana,
                (string)$this->phone1,
                (string)$this->phone2,
                (string)$this->phone3,
                $this->email,
            );
        return $pdf->render();
    }

    public function getJapaneseFonts(): array
    {
        return ArrayHelper::map(
            Font::find()->all(),
            'key',
            function (Font $font): string {
                return vsprintf('%s（%s）%s', [
                    $font->category->name,
                    $font->name,
                    $font->is_fixed ? '【等幅】' : '',
                ]);
            }
        );
    }

    public function getFixedWidthFont(?string $fontId): ?string
    {
        if ($fontId === null) {
            return null;
        }

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
