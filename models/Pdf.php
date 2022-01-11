<?php

declare(strict_types=1);

namespace app\models;

use TCPDF;
use Yii;
use yii\base\Model;

final class Pdf extends Model
{
    private const MAIN_ACCOUNT_TOP          = 6 + 6 + 3;
    private const MAIN_ACCOUNT_BOTTOM       = self::MAIN_ACCOUNT_TOP + 8;
    private const MAIN_ACCOUNT_1_LEFT       = 4;
    private const MAIN_ACCOUNT_1_RIGHT      = self::MAIN_ACCOUNT_1_LEFT + 5.08 * 5;
    private const MAIN_ACCOUNT_2_LEFT       = self::MAIN_ACCOUNT_1_RIGHT + 2.54;
    private const MAIN_ACCOUNT_2_RIGHT      = self::MAIN_ACCOUNT_2_LEFT + 5.08;
    private const MAIN_ACCOUNT_3_LEFT       = self::MAIN_ACCOUNT_2_RIGHT + 2.54;
    private const MAIN_ACCOUNT_3_RIGHT      = self::MAIN_ACCOUNT_3_LEFT + 5.08 * 7;
    private const MAIN_AMOUNT_TOP           = self::MAIN_ACCOUNT_TOP;
    private const MAIN_AMOUNT_BOTTOM        = self::MAIN_ACCOUNT_BOTTOM;
    private const MAIN_AMOUNT_LEFT          = self::MAIN_ACCOUNT_3_RIGHT + 5.08;
    private const MAIN_AMOUNT_RIGHT         = self::MAIN_AMOUNT_LEFT + 5.08 * 8;
    private const MAIN_ACCOUNT_NAME_TOP     = self::MAIN_ACCOUNT_BOTTOM + 1.75;
    private const MAIN_ACCOUNT_NAME_BOTTOM  = self::MAIN_ACCOUNT_NAME_TOP - 1.75 + 10 - 1.0;
    private const MAIN_ACCOUNT_NAME_LEFT    = self::MAIN_ACCOUNT_1_LEFT + 5.08 + 1.0;
    private const MAIN_ACCOUNT_NAME_RIGHT   = self::MAIN_ACCOUNT_3_RIGHT - 1.0;
    private const MAIN_NOTE_TOP             = self::MAIN_ACCOUNT_NAME_BOTTOM + 1.0;
    private const MAIN_NOTE_BOTTOM          = 57.5;
    private const MAIN_NOTE_LEFT            = self::MAIN_ACCOUNT_NAME_LEFT + 2.0;
    private const MAIN_NOTE_RIGHT           = self::MAIN_AMOUNT_RIGHT - 1.0;
    private const MAIN_POSTALCODE_MIDDLE    = 60.1;
    private const MAIN_POSTALCODE_1_LEFT    = 14.5;
    private const MAIN_POSTALCODE_1_RIGHT   = 22.0;
    private const MAIN_POSTALCODE_2_LEFT    = 25.5;
    // private const MAIN_POSTALCODE_2_RIGHT   = self::MAIN_AMOUNT_RIGHT;
    private const MAIN_ADDRESS_TOP          = 62.0;
    private const MAIN_ADDRESS_BOTTOM       = 75.0;
    private const MAIN_ADDRESS_LEFT         = 15.5;
    private const MAIN_ADDRESS_RIGHT        = 85.4;
    private const MAIN_NAME_TOP             = 75.5;
    private const MAIN_NAME_BOTTOM          = 85.0;
    private const MAIN_NAME_LEFT            = self::MAIN_ADDRESS_LEFT;
    private const MAIN_NAME_RIGHT           = 79.0;
    private const MAIN_PHONE_MIDDLE         = 87.0;
    private const MAIN_PHONE_1_LEFT         = 31.0;
    private const MAIN_PHONE_1_RIGHT        = 39.0;
    private const MAIN_PHONE_2_LEFT         = 41.5;
    private const MAIN_PHONE_2_RIGHT        = 50.0;
    private const MAIN_PHONE_3_LEFT         = 51.5;
    private const MAIN_PHONE_3_RIGHT        = 60.0;

    private const SUB_LEFT                  = 180 - 55;
    private const SUB_COMMON_LEFT           = self::SUB_LEFT + 6 + 5.08;
    private const SUB_COMMON_RIGHT          = self::SUB_COMMON_LEFT + 5.08 * 8;
    private const SUB_ACCOUNT_1_TOP         = 15;
    private const SUB_ACCOUNT_1_BOTTOM      = self::SUB_ACCOUNT_1_TOP + 8;
    private const SUB_ACCOUNT_2_TOP         = self::SUB_ACCOUNT_1_TOP;
    private const SUB_ACCOUNT_2_BOTTOM      = self::SUB_ACCOUNT_1_BOTTOM;
    private const SUB_ACCOUNT_3_TOP         = self::SUB_ACCOUNT_1_BOTTOM + 3;
    private const SUB_ACCOUNT_3_BOTTOM      = self::SUB_ACCOUNT_3_TOP + 8;
    private const SUB_ACCOUNT_1_LEFT        = self::SUB_COMMON_LEFT;
    private const SUB_ACCOUNT_1_RIGHT       = self::SUB_COMMON_LEFT + 5.08 * 5;
    private const SUB_ACCOUNT_2_LEFT        = self::SUB_ACCOUNT_1_RIGHT + 2.54;
    private const SUB_ACCOUNT_2_RIGHT       = self::SUB_ACCOUNT_2_LEFT + 5.08;
    private const SUB_ACCOUNT_3_LEFT        = self::SUB_COMMON_LEFT + 5.08;
    private const SUB_ACCOUNT_3_RIGHT       = self::SUB_COMMON_RIGHT;
    private const SUB_AMOUNT_TOP            = 15 + 8 + 3 + 8 + 10 + 3;
    private const SUB_AMOUNT_BOTTOM         = self::SUB_AMOUNT_TOP + 8;
    private const SUB_AMOUNT_LEFT           = self::SUB_COMMON_LEFT;
    private const SUB_AMOUNT_RIGHT          = self::SUB_COMMON_RIGHT;
    private const SUB_ACCOUNT_NAME_TOP      = self::SUB_ACCOUNT_3_BOTTOM + 1.0;
    private const SUB_ACCOUNT_NAME_BOTTOM   = self::SUB_ACCOUNT_NAME_TOP + 10 - 2.0;
    private const SUB_ACCOUNT_NAME_LEFT     = self::SUB_COMMON_LEFT + 1.0;
    private const SUB_ACCOUNT_NAME_RIGHT    = self::SUB_COMMON_RIGHT - 1.0;
    private const SUB_NAME_TOP              = self::SUB_AMOUNT_BOTTOM + 2;
    private const SUB_NAME_BOTTOM           = self::SUB_AMOUNT_BOTTOM + 24 - 2;
    private const SUB_NAME_LEFT             = self::SUB_COMMON_LEFT + 2.5;
    private const SUB_NAME_RIGHT            = self::SUB_COMMON_RIGHT - 2;

    public bool $debug = false;
    public bool $drawLines = false;
    public array $drawLineColor = [0x00, 0xa0, 0xe8];
    public string $fontNameForm = 'ipaexg';

    public string $fontNameJa = 'ipaexm';
    public string $fontNameNote = 'ipaexg';
    public bool $normalizeToWide = true;

    private ?array $lastRect = null;

    private ?TCPDF $pdf = null;

    /** @return void */
    public function init()
    {
        parent::init();
        $pdf = new TCPDF('L', 'mm', [114, 180], true, 'UTF-8');
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetCellPadding(0);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();
        $this->pdf = $pdf;

        if ($this->drawLines) {
            $this->drawLines();
        }
    }

    public function render(): string
    {
        return $this->pdf->Output('', 'S');
    }

    public function setAccount(string $account1, string $account2, string $account3): self
    {
        // {{{
        $pad = function (string $number, string $padChar, int $length): string {
            return substr(str_repeat($padChar, $length) . $number, -1 * $length);
        };
        $account1 = $pad($account1, '0', 5);
        $account3 = $pad($account3, ' ', 7);
        $this->drawNumbersToCells(
            self::MAIN_ACCOUNT_1_LEFT,
            self::MAIN_ACCOUNT_TOP,
            self::MAIN_ACCOUNT_1_RIGHT,
            self::MAIN_ACCOUNT_BOTTOM,
            $account1
        );
        $this->drawNumbersToCells(
            self::MAIN_ACCOUNT_2_LEFT,
            self::MAIN_ACCOUNT_TOP,
            self::MAIN_ACCOUNT_2_RIGHT,
            self::MAIN_ACCOUNT_BOTTOM,
            $account2
        );
        $this->drawNumbersToCells(
            self::MAIN_ACCOUNT_3_LEFT,
            self::MAIN_ACCOUNT_TOP,
            self::MAIN_ACCOUNT_3_RIGHT,
            self::MAIN_ACCOUNT_BOTTOM,
            $account3
        );
        $this->drawNumbersToCells(
            self::SUB_ACCOUNT_1_LEFT,
            self::SUB_ACCOUNT_1_TOP,
            self::SUB_ACCOUNT_1_RIGHT,
            self::SUB_ACCOUNT_1_BOTTOM,
            $account1
        );
        $this->drawNumbersToCells(
            self::SUB_ACCOUNT_2_LEFT,
            self::SUB_ACCOUNT_2_TOP,
            self::SUB_ACCOUNT_2_RIGHT,
            self::SUB_ACCOUNT_2_BOTTOM,
            $account2
        );
        $this->drawNumbersToCells(
            self::SUB_ACCOUNT_3_LEFT,
            self::SUB_ACCOUNT_3_TOP,
            self::SUB_ACCOUNT_3_RIGHT,
            self::SUB_ACCOUNT_3_BOTTOM,
            $account3
        );
        return $this;
        // }}}
    }

    public function setAmount(string $amount): self
    {
        // {{{
        $amount = substr(str_repeat(' ', 8) . $amount, -8);
        $this->drawNumbersToCells(
            self::MAIN_AMOUNT_LEFT,
            self::MAIN_AMOUNT_TOP,
            self::MAIN_AMOUNT_RIGHT,
            self::MAIN_AMOUNT_BOTTOM,
            $amount
        );
        $this->drawNumbersToCells(
            self::SUB_AMOUNT_LEFT,
            self::SUB_AMOUNT_TOP,
            self::SUB_AMOUNT_RIGHT,
            self::SUB_AMOUNT_BOTTOM,
            $amount
        );
        return $this;
        // }}}
    }

    public function setAccountName(string $name): self
    {
        // {{{
        $this->drawAccountName(
            self::MAIN_ACCOUNT_NAME_LEFT,
            self::MAIN_ACCOUNT_NAME_TOP,
            self::MAIN_ACCOUNT_NAME_RIGHT,
            self::MAIN_ACCOUNT_NAME_BOTTOM,
            $name,
            false
        );
        $this->drawAccountName(
            self::SUB_ACCOUNT_NAME_LEFT,
            self::SUB_ACCOUNT_NAME_TOP,
            self::SUB_ACCOUNT_NAME_RIGHT,
            self::SUB_ACCOUNT_NAME_BOTTOM,
            $name,
            true
        );
        return $this;
        // }}}
    }

    public function setNote(?string $note): self
    {
        // {{{
        $this->drawTextToBox(
            static::MAIN_NOTE_LEFT,
            static::MAIN_NOTE_TOP,
            static::MAIN_NOTE_RIGHT,
            static::MAIN_NOTE_BOTTOM,
            $note,
            'M',
            static::pt2mm(12),
            $this->fontNameNote
        );
        // }}}
        return $this;
    }

    public function setAddress(
        string $postalCode,
        string $prefecture,
        string $address1,
        string $address2,
        ?string $address3,
        string $name,
        ?string $kana,
        string $phone1,
        string $phone2,
        string $phone3,
        ?string $email
    ): self {
        // main {{{
        $address1 = trim($address1);
        $address2 = trim($address2);
        $address3 = trim($address3);
        $address = mb_convert_kana(
            trim(implode("\n", [
                $prefecture . ' ' . $address1,
                $address2,
                $address3,
            ])),
            $this->normalizeToWide ? 'ASKV' : 'aSKV',
            Yii::$app->charset
        );
        $this->drawPostalCode($postalCode);
        $this->drawTextToBox(
            static::MAIN_ADDRESS_LEFT,
            static::MAIN_ADDRESS_TOP,
            static::MAIN_ADDRESS_RIGHT,
            static::MAIN_ADDRESS_BOTTOM,
            $address,
            'T'
        );
        $this->drawName($name, $kana, $email);
        $this->drawPhone($phone1, $phone2, $phone3);
        // }}}
        // sub {{{
        $text = mb_convert_kana(
            trim(implode("\n", array_filter(
                [
                    $prefecture . ' ' . $address1,
                    $address2,
                    $address3,
                    $name,
                ],
                function (string $text): bool {
                    return $text !== '';
                }
            ))),
            $this->normalizeToWide ? 'ASKV' : 'aSKV',
            Yii::$app->charset
        );
        $this->drawTextToBox(
            static::SUB_NAME_LEFT,
            static::SUB_NAME_TOP,
            static::SUB_NAME_RIGHT,
            static::SUB_NAME_BOTTOM,
            $text,
            'M'
        );
        // }}}
        return $this;
    }

    private function drawNumbersToCells(
        float $left,
        float $top,
        float $right,
        float $bottom,
        string $numbers,
        float $fontSize = 4.5,
        float $paddingTop = 2.0
    ): self {
        // {{{
        $left = (float)number_format($left, 2, '.', '');
        $top = (float)number_format($top, 2, '.', '');
        $right = (float)number_format($right, 2, '.', '');
        $bottom = (float)number_format($bottom, 2, '.', '');
        $width = (float)number_format($right - $left, 2, '.', '');
        $height = (float)number_format($bottom - $top, 2, '.', '');
        if ($this->debug) {
            $this->pdf->Rect($left, $top, $width, $height, 'D'); // @codeCoverageIgnore
        }
        if ($paddingTop > 0) {
            $top = (float)number_format($top + $paddingTop, 2, '.', '');
            $height = (float)number_format($bottom - $top, 2, '.', '');
            if ($this->debug) {
                $this->pdf->Rect($left, $top, $width, $height, 'D'); // @codeCoverageIgnore
            }
        }
        $this->pdf->SetFont('ocrb_aizu_1_1', '', static::mm2pt($fontSize));
        if ($numbers != '') {
            $widthPerChar = $width / strlen($numbers);
            list(, $numbersHeight) = $this->calcTextSize($numbers);
            for ($i = 0; $i < strlen($numbers); ++$i) {
                $char = substr($numbers, $i, 1);
                if ($char === ' ') {
                    continue;
                }
                $this->pdf->SetXY(
                    $left + $widthPerChar * $i,
                    $top + ($height / 2 - $numbersHeight / 2)
                );
                $this->pdf->Cell(
                    $widthPerChar,
                    $numbersHeight,
                    $char,
                    0,      // border
                    0,      // ln
                    'C',    // align
                    false,  // fill
                    '',     // link
                    0,      // stretch
                    false,  // ignore_min_height
                    'T',    // calign
                    'M'     // valign
                );
            }
        }
        return $this;
        // }}}
    }

    private function drawTextToBox(
        float $left,
        float $top,
        float $right,
        float $bottom,
        string $text,
        string $valign = 'M',
        float $maxFontSize = 0,
        ?string $fontName = null
    ): self {
        // {{{
        if ($maxFontSize <= 0.1) {
            $maxFontSize = static::pt2mm(10.5);
        }
        $fontName ??= $this->fontNameJa;
        $left = (float)number_format($left, 2, '.', '');
        $top = (float)number_format($top, 2, '.', '');
        $right = (float)number_format($right, 2, '.', '');
        $bottom = (float)number_format($bottom, 2, '.', '');
        $width = (float)number_format($right - $left, 2, '.', '');
        $height = (float)number_format($bottom - $top, 2, '.', '');
        if ($this->debug) {
            $this->pdf->Rect($left, $top, $width, $height, 'D'); // @codeCoverageIgnore
        }
        $this->pdf->SetFont($fontName, '', 0);
        $fontSize = $this->calcFontSize($text, $width, $height, $maxFontSize);
        $this->pdf->SetFont('', '', static::mm2pt($fontSize));
        list ($textWidth, $textHeight) = $this->calcTextSize($text);
        $ypos = $valign === 'T' ? $top : ($top + ($height / 2 - $textHeight / 2));
        $this->pdf->SetXY($left, $ypos);
        $this->pdf->MultiCell(
            0,
            $textHeight,
            $text,
            0,      // border
            'L',    // align
            false,  // fill
            0       // ln
        );

        $this->lastRect = [
            $left,
            $ypos,
            $left + $textWidth,
            $ypos + $textHeight,
        ];

        return $this;
        // }}}
    }

    private function drawAccountName(
        float $left,
        float $top,
        float $right,
        float $bottom,
        string $name,
        bool $acceptFolding
    ): self {
        // {{{
        if (!$acceptFolding) {
            $this->drawAccountNameImpl($left, $top, $right, $bottom, $name, 0.0);
        } else {
            if (!$this->drawAccountNameImpl($left, $top, $right, $bottom, $name, 2.5)) {
                //FIXME: 文字の幅を考慮する
                $pos = (int)ceil(mb_strlen($name, 'UTF-8') / 2);
                $name = implode("\n", [
                    mb_substr($name, 0, $pos, 'UTF-8'),
                    mb_substr($name, $pos, null, 'UTF-8'),
                ]);
                $this->drawAccountNameImpl($left, $top, $right, $bottom, $name, 0.0);
            }
        }
        return $this;
        // }}}
    }

    private function drawAccountNameImpl(
        float $left,
        float $top,
        float $right,
        float $bottom,
        string $name,
        float $minFontSize
    ): bool {
        // {{{
        $left   = (float)number_format($left, 2, '.', '');
        $top    = (float)number_format($top, 2, '.', '');
        $right  = (float)number_format($right, 2, '.', '');
        $bottom = (float)number_format($bottom, 2, '.', '');
        $width  = (float)number_format($right - $left, 2, '.', '');
        $height = (float)number_format($bottom - $top, 2, '.', '');
        $innerWidth  = $width;
        $innerHeight = $height;
        if ($this->debug) {
            $this->pdf->Rect($left, $top, $width, $height, 'D'); // @codeCoverageIgnore
        }
        $this->pdf->SetFont($this->fontNameJa, '', 0);
        $fontSizeW = $this->calcFontSize($name, $innerWidth, INF);
        if ($fontSizeW < $minFontSize) {
            return false;
        }

        $fontSize = $this->calcFontSize($name, INF, $innerHeight);

        // 改行あるときは box にレンダリング
        if (strpos($name, "\n") !== false) {
            $this->drawTextToBox($left, $top, $right, $bottom, $name, 'M', $fontSize);
            return true;
        }

        $this->pdf->SetFont('', '', static::mm2pt($fontSize));
        list ($textWidth, $textHeight) = $this->calcTextSize($name);
        $stretch = false;
        if ($textWidth > $innerWidth) {
            // 長い文字列だったので横方向が足りていない
            if ($innerWidth / $textWidth >= 0.75) {
                // 縦長文字で対応できるレベル
                $textWidth = $textWidth * 0.75;
                $stretch = true;
            } elseif ($innerWidth / $textWidth >= 0.4) {
                // まあなんとか…
                $textWidth = $innerWidth;
                $stretch = true;
            } else {
                // 縦長文字で対応できないので普通に縮小する
                $fontSize = $this->calcFontSize($name, $innerWidth / 0.75, $innerHeight);
                list ($textWidth, $textHeight) = $this->calcTextSize($name);
                $textWidth *= 0.75;
                $stretch = true;
            }
        }

        $putLeft = $left + ($innerWidth / 2 - $textWidth / 2);
        $putTop = $top + ($innerHeight / 2 - $textHeight / 2);
        $this->pdf->SetXY($putLeft, $putTop);
        $this->pdf->Cell(
            $textWidth,
            $textHeight,
            $name,
            0,      // border
            0,      // ln
            'C',    // align
            false,  // fill
            '',     // link
            $stretch ? 2 : 0, // stretch
            false,  // ignore_min_height
            'T',    // calign
            'M'     // valign
        );
        return true;
        // }}}
    }

    public function drawPostalCode(string $code): self
    {
        // {{{
        $code1 = substr($code, 0, 3);
        $code2 = substr($code, 3, 4);
        $this->pdf->SetFont('ocrb_aizu_1_1', '', 0);
        $fontSize = $this->calcFontSize(
            $code1,
            static::MAIN_POSTALCODE_1_RIGHT - static::MAIN_POSTALCODE_1_LEFT,
            INF
        );
        $this->pdf->SetFont('', '', static::mm2pt($fontSize));
        list(, $textHeight) = $this->calcTextSize($code1);
        $yPos = static::MAIN_POSTALCODE_MIDDLE - $textHeight / 2;
        $this->pdf->SetXY(static::MAIN_POSTALCODE_1_LEFT, $yPos);
        $this->pdf->Cell(
            static::MAIN_POSTALCODE_1_RIGHT - static::MAIN_POSTALCODE_1_LEFT,
            $textHeight,
            $code1,
            0,      // border
            0,      // ln
            'R',    // align
            false,  // fill
            '',     // link
            0,      // stretch
            false,  // ignore_min_height
            'T',    // calign
            'M'     // valign
        );
        $this->pdf->SetXY(static::MAIN_POSTALCODE_2_LEFT, $yPos);
        $this->pdf->Cell(
            0,
            $textHeight,
            $code2,
            0,      // border
            0,      // ln
            'L',    // align
            false,  // fill
            '',     // link
            0,      // stretch
            false,  // ignore_min_height
            'T',    // calign
            'M'     // valign
        );
        return $this;
        // }}}
    }

    public function drawName(string $name, ?string $kana, ?string $email): self
    {
        // name, kana {{{
        $name = mb_convert_kana(
            trim($name),
            $this->normalizeToWide ? 'ASKV' : 'aSKV',
            Yii::$app->charset
        );
        $kana = mb_convert_kana(
            trim($kana),
            $this->normalizeToWide ? 'ASCKV' : 'aSCKV',
            Yii::$app->charset
        );
        $boxHeight = static::MAIN_NAME_BOTTOM - static::MAIN_NAME_TOP;
        $nameMaxHeight = (float)number_format($boxHeight * 0.618034, 2, '.', '');
        $kanaMaxHeight = (float)number_format($boxHeight - $nameMaxHeight, 2, '.', '');
        $top = ($kana === '')
            ? (static::MAIN_NAME_TOP + ($boxHeight / 2 - $nameMaxHeight / 2))
            : (static::MAIN_NAME_TOP + $kanaMaxHeight);
        $this->drawTextToBox(
            static::MAIN_NAME_LEFT,
            $top,
            static::MAIN_NAME_RIGHT,
            $top + $nameMaxHeight,
            $name,
            'M',
            20.0
        );
        $nameRight = max($this->lastRect[2], static::MAIN_NAME_LEFT);
        $kanaRight = static::MAIN_NAME_LEFT;
        if ($kana !== '') {
            $this->pdf->SetFont($this->fontNameJa, '', 0);
            $fontSize = $this->calcFontSize(
                $kana,
                (static::MAIN_NAME_RIGHT - static::MAIN_NAME_LEFT) / 0.75,
                $kanaMaxHeight + 0.8
            );
            $this->pdf->SetFont('', '', static::mm2pt($fontSize));
            list($textWidth, ) = $this->calcTextSize($kana);
            $this->drawAccountName(
                static::MAIN_NAME_LEFT,
                static::MAIN_NAME_TOP,
                static::MAIN_NAME_LEFT + $textWidth * 0.75,
                static::MAIN_NAME_TOP + $kanaMaxHeight + 0.8,
                $kana,
                false
            );
            $kanaRight = max($this->lastRect[2], static::MAIN_NAME_LEFT);
        }
        // }}}
        // email {{{
        if (trim((string)$email) !== '') {
            $left = max($nameRight, $kanaRight, static::MAIN_NAME_LEFT) + 3;
            $this->pdf->SetFont('robotomono', '', 0);
            $fontSize = $this->calcFontSize($email, (static::MAIN_NAME_RIGHT - $left), 3.5);
            $this->pdf->SetFont('', '', static::mm2pt($fontSize));
            list($textWidth, ) = $this->calcTextSize($email);
            $this->drawTextToBox(
                $left,
                $top + 1.8,
                static::MAIN_NAME_RIGHT,
                $top + $nameMaxHeight,
                $email,
                'M',
                $fontSize,
                'robotomono'
            );
        }
        // }}}
        return $this;
    }

    public function drawPhone(string $phone1, string $phone2, string $phone3): self
    {
        // {{{
        $this->pdf->SetFont('ocrb_aizu_1_1', '', 0);
        $fontSize = min(
            $this->calcFontSize(
                $phone1,
                static::MAIN_PHONE_1_RIGHT - static::MAIN_PHONE_1_LEFT,
                INF
            ),
            $this->calcFontSize(
                $phone2,
                static::MAIN_PHONE_2_RIGHT - static::MAIN_PHONE_2_LEFT,
                INF
            ),
            $this->calcFontSize(
                $phone3,
                static::MAIN_PHONE_3_RIGHT - static::MAIN_PHONE_3_LEFT,
                INF
            )
        );
        $this->pdf->SetFont('', '', static::mm2pt($fontSize));
        list(, $textHeight) = $this->calcTextSize($phone1);
        $yPos = static::MAIN_PHONE_MIDDLE - $textHeight / 2;
        $list = [
            [
                'text' => $phone1,
                'left' => static::MAIN_PHONE_1_LEFT,
                'right' => static::MAIN_PHONE_1_RIGHT,
            ],
            [
                'text' => $phone2,
                'left' => static::MAIN_PHONE_2_LEFT,
                'right' => static::MAIN_PHONE_2_RIGHT,
            ],
            [
                'text' => $phone3,
                'left' => static::MAIN_PHONE_3_LEFT,
                'right' => static::MAIN_PHONE_3_RIGHT,
            ],
        ];
        foreach ($list as $item) {
            $this->pdf->SetXY($item['left'], $yPos);
            $this->pdf->Cell(
                $item['right'] - $item['left'],
                $textHeight,
                $item['text'],
                0,      // border
                0,      // ln
                'C',    // align
                false,  // fill
                '',     // link
                0,      // stretch
                false,  // ignore_min_height
                'T',    // calign
                'M'     // valign
            );
        }
        return $this;
        // }}}
    }

    private function drawLines(): self
    {
        // 通信欄全体に「使用禁止」 // {{{
        call_user_func_array(
            fn (int $r, int $g, int $b) => $this->pdf->SetTextColor($r, $g, $b),
            array_map(
                fn (int $color): int => (int)floor(($color + 512) / 3),
                $this->drawLineColor,
            )
        );
        $this->pdf->SetFont($this->fontNameForm);
        $size = $this->calcFontSize('使用禁止', 111.76, 57, 30);
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt($size));
        list(, $textHeight) = $this->calcTextSize('使用禁止');
        $this->pdf->SetXY(9.08, 33 + 57 / 2 - $textHeight / 2);
        $this->pdf->Cell(111.76, $textHeight, '使用禁止', 0, 0, 'C', false, '', 0, false, 'T', 'M');
        // }}}

        $this->pdf->SetTextColor(
            $this->drawLineColor[0],
            $this->drawLineColor[1],
            $this->drawLineColor[2],
        );
        $this->drawDashedLines();
        $this->drawMainLines();
        $this->drawSubLines();

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetLineStyle([
            'width' => 0.1,
            'cap' => 'square',
            'join' => 'square',
            'dash' => 0,
            'color' => [0, 0, 0],
        ]);
        return $this;
    }

    private function drawDashedLines(): self
    {
        // {{{
        $this->pdf->SetLineStyle([
            'width' => 0.15875,
            'cap' => 'square',
            'join' => 'square',
            'dash' => 2,
            'color' => $this->drawLineColor,
        ]);

        // メインとサブの境界
        $this->pdf->Line(static::SUB_LEFT, 0, static::SUB_LEFT, 114);

        // これより下部には～
        $this->pdf->Line(0, 94, 85.84, 94);
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt(2.5));
        $text = '裏面の注意事項をお読みください。（ゆうちょ銀行）（承認番号　第　　　　号）';
        list(, $textHeight) = $this->calcTextSize($text);
        $this->pdf->SetXY(4, 90 + (4 / 2 - $textHeight / 2));
        $this->pdf->Cell(81.84, $textHeight, $text, 0, 0, 'L', false, '', 2, false, 'T', 'M');
        $text = 'これより下部には何も記入しないでください。';
        list(, $textHeight) = $this->calcTextSize($text);
        $this->pdf->SetXY(4, 94 + (4 / 2 - $textHeight / 2));
        $this->pdf->Cell(0, $textHeight, $text, 0, 0, 'L', false, '', 0, false, 'T', 'M');

        // 使用禁止理由を記載
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt(1.75));
        $lines = [
            '払込取扱票の私製は原則として禁止されています。',
            '払込取扱票の私製はゆうちょ銀行の審査を受け、許可を受ける必要があります。',
            '罫線等の印刷はあくまで出力イメージの確認用に用意しており、',
            '実際の払込に利用することはできませんし、利用することも想定していません。',
            '正規の払込取扱票をご準備の上、通常モードで出力してご利用ください。',
        ];
        $y = 98;
        foreach ($lines as $text) {
            list(, $textHeight) = $this->calcTextSize($text);
            $this->pdf->SetXY(4, $y);
            $this->pdf->Cell(0, 0, $text, 0, 0, 'L', false, '', 0, false, 'T', 'T');
            $y += $textHeight;
        }

        return $this;
        // }}}
    }

    private function drawMainLines(): self
    {
        // {{{
        // 太線
        $this->pdf->SetLineStyle([
            'width' => 0.3175,
            'cap' => 'square',
            'join' => 'square',
            'dash' => 0,
            'color' => $this->drawLineColor,
        ]);
        $this->pdf->Line(4, 6, 29.4, 6);
        $this->pdf->Line(4, 6, 4, 23);
        $this->pdf->Line(12.5, 6, 12.5, 12);
        $this->pdf->Line(29.4, 6, 29.4, 12);
        $this->pdf->Line(4, 12, 120.84, 12);
        $this->pdf->Line(120.84, 12, 120.84, 33);
        $this->pdf->Line(4, 23, 75.12, 23);
        $this->pdf->Line(75.12, 23, 75.12, 33);
        $this->pdf->Line(75.12, 33, 120.84, 33);

        // 左上 00
        $this->pdf->SetFont('ocrb_aizu_1_1', '', static::mm2pt(3.5));
        list(, $textHeight) = $this->calcTextSize('00');
        $this->pdf->SetXY(4, 6 + (6 / 2 - $textHeight / 2));
        $this->pdf->Cell(8.5, $textHeight, '00', 0, 0, 'C', false, '', 0, false, 'T', 'M');

        // 左上支社名？
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt(3.25));
        list(, $textHeight) = $this->calcTextSize('横浜');
        $this->pdf->SetXY(12.5, 6 + (6 / 2 - $textHeight / 2));
        $this->pdf->Cell(16.9, $textHeight, '横浜', 0, 0, 'C', false, '', 0, false, 'T', 'M');

        // 払込取扱票
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt(4.5));
        $text = '払込取扱票';
        list(, $textHeight) = $this->calcTextSize($text);
        $this->pdf->SetXY(39.56, 4 + (8 / 2 - $textHeight / 2));
        $this->pdf->Cell(55.88, $textHeight, $text, 0, 0, 'J', false, '', 4, false, 'T', 'M');

        $this->pdf->SetLineStyle([
            'width' => 0.15875,
            'cap' => 'square',
            'join' => 'square',
            'dash' => 0,
            'color' => $this->drawLineColor,
        ]);

        // 口座番号記号
        $this->pdf->line(75.12, 12, 75.12, 33);
        $this->pdf->Line(4, 15, 75.12, 15);
        $xposes = [1, 2, 3, 4, '5', '5.5', '6.5', '7', '8', 9, 10, '11', 12, 13];
        foreach ($xposes as $xpos) {
            $this->pdf->SetLineStyle([
                'width' => 0.15875,
                'cap' => 'square',
                'join' => 'square',
                'dash' => is_string($xpos) ? 0 : 2,
                'color' => $this->drawLineColor,
            ]);
            $x = 4 + 5.08 * (float)$xpos;
            $this->pdf->Line($x, 15, $x, 23);
        }
        // =
        $this->pdf->SetLineStyle([
            'width' => 0.15875,
            'cap' => 'square',
            'join' => 'square',
            'dash' => 0,
            'color' => $this->drawLineColor,
        ]);
        foreach ([5, 6.5] as $xpos) {
            $x = 4 + 5.08 * $xpos;
            $this->pdf->Rect($x, 15 + 8 / 2 - 1.27 / 2, 2.54, 1.27, 'DF', [], array_map(
                function (int $color): int {
                    return (int)floor(($color + 512) / 3);
                },
                $this->drawLineColor
            ));
        }
        // 払込取扱票
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt(2.5));
        $text = '口座記号番号';
        list(, $textHeight) = $this->calcTextSize($text);
        $this->pdf->SetXY(4 + 12.7, 12 + (3 / 2 - $textHeight / 2));
        $this->pdf->Cell(45.72, $textHeight, $text, 0, 0, 'J', false, '', 4, false, 'T', 'M');

        // 金額
        $this->pdf->Line(80.20, 15, 120.84, 15);
        $this->pdf->Line(75.12, 23, 120.84, 23);
        $xposes = ['0', 1, '2', 3, 4, '5', 6, 7];
        foreach ($xposes as $xpos) {
            $this->pdf->SetLineStyle([
                'width' => 0.15875,
                'cap' => 'square',
                'join' => 'square',
                'dash' => is_string($xpos) ? 0 : 2,
                'color' => $this->drawLineColor,
            ]);
            $x = 4 + 5.08 * ((float)$xpos + 15);
            $this->pdf->Line($x, 12, $x, 23);
        }
        $this->pdf->SetLineStyle([
            'width' => 0.15875,
            'cap' => 'square',
            'join' => 'square',
            'dash' => 0,
            'color' => $this->drawLineColor,
        ]);
        //TODO: 縦書き: 金額
        $texts = ['千', '百', '十', '万', '千', '百', '十', '円'];
        list(, $textHeight) = $this->calcTextSize('円');
        foreach ($texts as $i => $text) {
            $this->pdf->SetXY(80.2 + $i * 5.08, 12 + (3 / 2 - $textHeight / 2));
            $this->pdf->Cell(5.08, $textHeight, $text, 0, 0, 'C', false, '', 0, false, 'T', 'M');
        }

        // 料金
        $this->pdf->Line(80.2, 23, 80.2, 33);
        $this->pdf->Line(95.44, 23, 95.44, 33);
        //TODO: 縦書き: 料金

        // 備考
        $this->pdf->Line(100.52, 23, 100.52, 33);
        //TODO: 縦書き: 備考

        // 加入者名
        $this->pdf->Line(4, 23, 4, 90);
        $this->pdf->Line(9.08, 23, 9.08, 90);
        $this->pdf->Line(4, 33, 75.12, 33);
        $this->pdf->Line(120.84, 33, 120.84, 98);
        $this->pdf->Line(4, 90, 85.84, 90); // 依頼人の下
        //TODO: 縦書き: 加入者名

        // 通信欄
        //TODO: 縦書き: 通信欄・ご依頼人

        // 依頼人
        //TODO: 依頼人のテキスト諸々

        // 日附印
        $this->pdf->Line(85.84, 70, 120.84, 70);
        $this->pdf->Line(85.84, 70, 85.84, 98);
        $this->pdf->Line(90.84, 70, 90.84, 98);
        $this->pdf->Line(85.84, 98, 120.84, 98);
        //TODO: 縦書き: 日附印

        return $this;
        // }}}
    }

    private function drawSubLines(): self
    {
        // 振替払込請求書兼受領証
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt(4));
        $text = '振替払込請求書兼受領証';
        list(, $textHeight) = $this->calcTextSize($text);
        $this->pdf->SetXY(133.54, 4 + (8 / 2 - $textHeight / 2));
        $this->pdf->Cell(40.64, $textHeight, $text, 0, 0, 'J', false, '', 1, false, 'T', 'M');

        $this->pdf->SetLineStyle([
            'width' => 0.15875,
            'cap' => 'square',
            'join' => 'square',
            'dash' => 0,
            'color' => $this->drawLineColor,
        ]);

        $this->pdf->Line(131, 15, 131, 111);
        $this->pdf->Line(136.08, 15, 136.08, 111);
        $this->pdf->Line(176.72, 23, 176.72, 111);

        // 口座番号記号
        $this->pdf->Line(131, 15, 169.1, 15);
        $this->pdf->Line(169.1, 15, 169.1, 23);
        $this->pdf->Line(136.08, 23, 176.72, 23);
        $this->pdf->Line(136.08, 26, 176.72, 26);
        $this->pdf->Line(131, 34, 176.72, 34);
        $xposes = [1, 2, 3, 4, '5', '5.5'];
        foreach ($xposes as $xpos) {
            $this->pdf->SetLineStyle([
                'width' => 0.15875,
                'cap' => 'square',
                'join' => 'square',
                'dash' => is_string($xpos) ? 0 : 2,
                'color' => $this->drawLineColor,
            ]);
            $x = 136.08 + 5.08 * (float)$xpos;
            $this->pdf->Line($x, 15, $x, 23);
        }
        $xposes = ['2', 3, 4, '5', 6, 7];
        foreach ($xposes as $xpos) {
            $this->pdf->SetLineStyle([
                'width' => 0.15875,
                'cap' => 'square',
                'join' => 'square',
                'dash' => is_string($xpos) ? 0 : 2,
                'color' => $this->drawLineColor,
            ]);
            $x = 136.08 + 5.08 * (float)$xpos;
            $this->pdf->Line($x, 26, $x, 34);
        }
        // =
        $this->pdf->SetLineStyle([
            'width' => 0.15875,
            'cap' => 'square',
            'join' => 'square',
            'dash' => 0,
            'color' => $this->drawLineColor,
        ]);
        $this->pdf->Rect(161.48, 15 + 8 / 2 - 1.27 / 2, 2.54, 1.27, 'DF', [], array_map(
            function (int $color): int {
                return (int)floor(($color + 512) / 3);
            },
            $this->drawLineColor
        ));

        // 加入者名
        $this->pdf->Line(131, 44, 176.72, 44);

        // 金額
        $this->pdf->Line(136.08, 47, 176.72, 47);
        $this->pdf->Line(131, 55, 176.72, 55);
        $xposes = [1, '2', 3, 4, '5', 6, 7];
        foreach ($xposes as $xpos) {
            $this->pdf->SetLineStyle([
                'width' => 0.15875,
                'cap' => 'square',
                'join' => 'square',
                'dash' => is_string($xpos) ? 0 : 2,
                'color' => $this->drawLineColor,
            ]);
            $x = 136.08 + 5.08 * (float)$xpos;
            $this->pdf->Line($x, 44, $x, 55);
        }
        $this->pdf->SetLineStyle([
            'width' => 0.15875,
            'cap' => 'square',
            'join' => 'square',
            'dash' => 0,
            'color' => $this->drawLineColor,
        ]);
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt(2.5));
        $texts = ['千', '百', '十', '万', '千', '百', '十', '円'];
        list(, $textHeight) = $this->calcTextSize('円');
        foreach ($texts as $i => $text) {
            $this->pdf->SetXY(136.08 + 5.08 * $i, 44 + (3 / 2 - $textHeight / 2));
            $this->pdf->Cell(5.08, $textHeight, $text, 0, 0, 'C', false, '', 0, false, 'T', 'M');
        }

        // 依頼人
        $this->pdf->Line(131, 79, 176.72, 79);

        // 日附印
        $this->pdf->Line(146.24, 79, 146.24, 111);
        $this->pdf->Line(146.24, 83, 176.72, 83);
        $this->pdf->Line(131, 111, 176.72, 111);
        $this->pdf->Line(131, 94, 146.24, 94); // 料金と備考の間の線
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt(3.0));
        $text = '日附印';
        list(, $textHeight) = $this->calcTextSize($text);
        $this->pdf->SetXY(148.78, 79 + (4 / 2 - $textHeight / 2));
        $this->pdf->Cell(25.44, $textHeight, $text, 0, 0, 'J', false, '', 4, false, 'T', 'M');

        // 料金
        $this->pdf->SetFont($this->fontNameForm);
        $text = '（消費税込み）';
        $size = $this->calcFontSize($text, 10.16, INF);
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt($size));
        $this->pdf->SetXY(136.08, 79.1);
        $this->pdf->Cell(10.16, 0, $text, 0, 0, 'C', false, '', 0, false, 'T', 'M');
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt(2.5));
        $this->pdf->SetXY(136.08, 94 - 0.5);
        $this->pdf->Cell(10.16 - 0.5, 0, '円', 0, 0, 'R', false, '', 0, false, 'B', 'B');

        // 訂正印
        $this->pdf->SetFont($this->fontNameForm, '', static::mm2pt(1.75));
        $this->pdf->SetXY(131, 111.5);
        $text = 'この受領証は、大切に保管してください。';
        $this->pdf->Cell(0, 0, $text, 0, 0, 'L', false, '', 0, false, 'T', 'T');

        return $this;
        // }}}
    }

    private function calcTextSize(string $text): array
    {
        // {{{
        $lines = explode("\n", $text);
        $this->pdf->SetXY(0, 0);
        return [
            // width
            max(array_map(
                function (string $text): float {
                    return (float)$this->pdf->GetStringWidth($text);
                },
                $lines
            )),
            // height
            array_reduce(
                $lines,
                function (float $carry, string $item): float {
                    return $carry + $this->pdf->GetStringHeight(0, $item, false, false);
                },
                0.0
            ),
        ];
        // }}}
    }

    private function calcFontSize(
        string $text,
        float $width,
        float $height,
        float $maxFontSize = 20.0,
        float $minFontSize = 0.1
    ): float {
        // {{{
        for ($i = 0;; ++$i) {
            $fontSize = (float)number_format($maxFontSize - 0.1 * $i, 2, '.', '');
            if ($fontSize <= $minFontSize || $fontSize <= 0) {
                return $minFontSize;
            }
            $this->pdf->SetFont('', '', static::mm2pt($fontSize));
            list($textWidth, $textHeight) = $this->calcTextSize($text);
            if ($textWidth <= $width && $textHeight <= $height) {
                return $fontSize;
            }
        }
        return $minFontSize;
        // }}}
    }

    private static function mm2pt(float $mm): float
    {
        return $mm * 72.0 / 25.4;
    }

    private static function pt2mm(float $pt): float
    {
        return $pt * 25.4 / 72.0;
    }
}
