<?php

declare(strict_types=1);

namespace tests\unit\models;

use Codeception\Test\Unit;
use UnitTester;
use Yii;
use app\models\HaraikomiForm;

final class HaraikomiFormTest extends Unit
{
    protected UnitTester $tester;

    public function testGetJapaneseFonts(): void
    {
        $model = Yii::createObject(HaraikomiForm::class);
        $fonts = $model->japaneseFonts;
        $this->assertIsArray($fonts);
        $this->assertEquals('明朝体（IPAex明朝）', $fonts['ipaexm']);
        $this->assertEquals('明朝体（IPA明朝）【等幅】', $fonts['ipam']);
        $this->assertEquals('ゴシック体（IPAexゴシック）', $fonts['ipaexg']);
        $this->assertEquals('ゴシック体（IPAゴシック）【等幅】', $fonts['ipag']);
    }

    /** @dataProvider getFixedWidthFontMap */
    public function testGetFixedWidthFont(string $fontKey, ?string $fixedFontKey): void
    {
        $this->assertEquals(
            $fixedFontKey,
            Yii::createObject(HaraikomiForm::class)->getFixedWidthFont($fontKey)
        );
    }

    public function getFixedWidthFontMap(): array
    {
        return [
            'ipaexg' => ['ipaexg', 'ipag'],
            'ipaexm' => ['ipaexm', 'ipam'],
            'mplus1p' => ['mplus1p', 'mplus1m'],
            'ipag' => ['ipag', null],
            'ipam' => ['ipam', null],
        ];
    }

    public function testValidateValidAttrs(): void
    {
        $model = Yii::createObject(array_merge(
            ['class' => HaraikomiForm::class],
            $this->getValidAttributeValues(),
        ));
        $this->assertTrue($model->validate());
    }

    public function testValidateRequiredAttrs(): void
    {
        $oldLang = Yii::$app->language;
        try {
            Yii::$app->language = 'en-US';

            $attrs = [
                'account1' => '',
                'account2' => '',
                'account3' => '',
                'amount' => '',
                'account_name' => '',
                'postal_code' => '',
                'pref_id' => '',
                'address1' => '',
                'address2' => '',
                'address3' => '',
                'name' => '',
                'kana' => '',
                'phone1' => '',
                'phone2' => '',
                'phone3' => '',
                'email' => '',
                'note' => '',
                'font_ja' => '',
                'use_fixed' => '',
                'draw_form' => '',
            ];

            $model = Yii::createObject(array_merge(
                ['class' => HaraikomiForm::class],
                $attrs,
            ));
            $this->assertFalse($model->validate());

            $reqAttrs = $this->getRequiredAttributes();
            foreach (array_keys($attrs) as $attr) {
                if (in_array($attr, $reqAttrs, true)) {
                    $this->assertTrue($model->hasErrors($attr));

                    $error = $model->getFirstError($attr);
                    $this->assertTrue(str_contains($error, ' cannot be blank.'));
                } else {
                    $this->assertFalse($model->hasErrors($attr));
                }
            }
        } finally {
            Yii::$app->language = $oldLang;
        }
    }

    public function testMakePdf(): void
    {
        $model = Yii::createObject(array_merge(
            ['class' => HaraikomiForm::class],
            $this->getValidAttributeValues(),
        ));
        $pdf = $model->makePdf();
        $this->assertIsString($pdf);
        $this->assertEquals('%PDF-1.', substr($pdf, 0, 7));

        // 縦長表記や改行でなんとかする程度の長い名前
        $model->account_name = str_repeat('加入者名', 5);
        $model->kana = '';
        $pdf = $model->makePdf();
        $this->assertIsString($pdf);
        $this->assertEquals('%PDF-1.', substr($pdf, 0, 7));
    }

    public function testMakePdfWithVeryLongName(): void
    {
        // どうにもならない程度の恐ろしく長い名前（実際にはあり得なかろう）
        $model = Yii::createObject(array_merge(
            ['class' => HaraikomiForm::class],
            $this->getValidAttributeValues(),
            ['account_name' => str_repeat('加入者名', 10)],
        ));
        $pdf = $model->makePdf();
        $this->assertIsString($pdf);
        $this->assertEquals('%PDF-1.', substr($pdf, 0, 7));
    }

    private function getValidAttributeValues(): array
    {
        $attrs = [];
        foreach ($this->getValidAttributeInformation() as $k => $v) {
            $kt = explode(':', $k);
            $attrs[$kt[0]] = $v;
        }
        return $attrs;
    }

    private function getRequiredAttributes(): array
    {
        $results = [];
        foreach ($this->getValidAttributeInformation() as $k => $v) {
            $kt = explode(':', $k);
            if (in_array('req', $kt, true)) {
                $results[] = $kt[0];
            }
        }
        return $results;
    }

    private function getValidAttributeInformation(): array
    {
        return [
            'account1:req:int<1,99999>' => '12345',
            'account2:req:int<1,9>' => '6',
            'account3:req:int<1,9999999>' => '789012',
            'amount:req:int<1,>' => '10000',
            'account_name:req:str' => '加入者名',
            'postal_code:str' => '1000001',
            'pref_id:int<1,47>' => '13',
            'address1:str' => '千代田区',
            'address2:str' => '千代田',
            'address3:str' => '１－１',
            'name:str' => '相沢陽菜',
            'kana:str' => 'アイザワヒナ',
            'phone1:int<1,99999>' => '090',
            'phone2:int<1,9999>' => '1234',
            'phone3:int<0,9999>' => '5678',
            'email:email' => 'hina@example.com',
            'note:str' => "通信欄\nABCDEFGH",
            'font_ja:req:str' => 'ipaexm',
            'use_fixed:bool' => '1',
            'draw_form:bool' => '1',
        ];
    }
}
