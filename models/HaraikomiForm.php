<?php

declare(strict_types=1);

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
    public $email;
    public $note;
    public $font_ja;
    public $use_fixed;
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
        return [
            'ipaexm'            => '明朝体（IPAex明朝）',
            'ipam'              => '明朝体（IPA明朝・等幅）',
            'umepmo3'           => '明朝体（梅P明朝）',
            'ipaexg'            => 'ゴシック体（IPAexゴシック）',
            'ipag'              => 'ゴシック体（IPAゴシック・等幅）',
            'genshingothic'     => 'ゴシック体（源真ゴシック）',
            'umepgo4'           => 'ゴシック体（梅Pゴシック）',
            'mplus1p'           => 'ゴシック体（M+ 1p）',
            'genjyuugothic'     => '丸ゴシック体（源柔ゴシック）',
            'mikachanp'         => '手書き風（みかちゃん-P）',
            'nyashi'            => '手書き風（にゃしぃフォント改二／睦月）',
            'nyashi_friends'    => '手書き風（にゃしぃフレンズ／如月）',
        ];
    }

    public function getFixedWidthFont(string $fontId): ?string
    {
        $map = [
            'ipaexg' => 'ipag',
            'ipaexm' => 'ipam',
        ];
        if ($fixedFont = ($map[$fontId] ?? null)) {
            // $map の設定フォントが利用可能フォントに挙げられていることを
            // 念のため確認する
            $availableFonts = array_keys($this->getJapaneseFonts());
            if (in_array($fixedFont, $availableFonts, true)) {
                return $fixedFont;
            }
        }

        return null;
    }
}
