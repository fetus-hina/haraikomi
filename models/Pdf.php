<?php
namespace app\models;

use TCPDF;
use Yii;
use yii\base\Model;

class Pdf extends Model
{
    const MAIN_ACCOUNT_TOP          = 6 + 6 + 3;
    const MAIN_ACCOUNT_BOTTOM       = self::MAIN_ACCOUNT_TOP + 8;
    const MAIN_ACCOUNT_1_LEFT       = 4;
    const MAIN_ACCOUNT_1_RIGHT      = self::MAIN_ACCOUNT_1_LEFT + 5.08 * 5;
    const MAIN_ACCOUNT_2_LEFT       = self::MAIN_ACCOUNT_1_RIGHT + 2.54;
    const MAIN_ACCOUNT_2_RIGHT      = self::MAIN_ACCOUNT_2_LEFT + 5.08;
    const MAIN_ACCOUNT_3_LEFT       = self::MAIN_ACCOUNT_2_RIGHT + 2.54;
    const MAIN_ACCOUNT_3_RIGHT      = self::MAIN_ACCOUNT_3_LEFT + 5.08 * 7;
    const MAIN_AMOUNT_TOP           = self::MAIN_ACCOUNT_TOP;
    const MAIN_AMOUNT_BOTTOM        = self::MAIN_ACCOUNT_BOTTOM;
    const MAIN_AMOUNT_LEFT          = self::MAIN_ACCOUNT_3_RIGHT + 5.08;
    const MAIN_AMOUNT_RIGHT         = self::MAIN_AMOUNT_LEFT + 5.08 * 8;
    const MAIN_ACCOUNT_NAME_TOP     = self::MAIN_ACCOUNT_BOTTOM + 1.75;
    const MAIN_ACCOUNT_NAME_BOTTOM  = self::MAIN_ACCOUNT_NAME_TOP - 1.75 + 10;
    const MAIN_ACCOUNT_NAME_LEFT    = self::MAIN_ACCOUNT_1_LEFT + 5.08;
    const MAIN_ACCOUNT_NAME_RIGHT   = self::MAIN_ACCOUNT_3_RIGHT;
    const MAIN_NOTE_TOP             = self::MAIN_ACCOUNT_NAME_BOTTOM;
    const MAIN_NOTE_BOTTOM          = 57.5;
    const MAIN_NOTE_LEFT            = self::MAIN_ACCOUNT_NAME_LEFT + 2.0;
    const MAIN_NOTE_RIGHT           = self::MAIN_AMOUNT_RIGHT;
    const MAIN_POSTALCODE_MIDDLE    = 60.2;
    const MAIN_POSTALCODE_1_LEFT    = 15.0;
    const MAIN_POSTALCODE_1_RIGHT   = 22.0;
    const MAIN_POSTALCODE_2_LEFT    = 25.5;
    const MAIN_POSTALCODE_2_RIGHT   = self::MAIN_AMOUNT_RIGHT;
    const MAIN_ADDRESS_TOP          = 62.0;
    const MAIN_ADDRESS_BOTTOM       = 75.0;
    const MAIN_ADDRESS_LEFT         = 15.5;
    const MAIN_ADDRESS_RIGHT        = 85.4;
    const MAIN_NAME_TOP             = 75.5;
    const MAIN_NAME_BOTTOM          = 85.0;
    const MAIN_NAME_LEFT            = self::MAIN_ADDRESS_LEFT;
    const MAIN_NAME_RIGHT           = 79.0;
    const MAIN_PHONE_MIDDLE         = 87.0;
    const MAIN_PHONE_1_LEFT         = 31.0;
    const MAIN_PHONE_1_RIGHT        = self::MAIN_PHONE_1_LEFT + 8.0;
    const MAIN_PHONE_2_LEFT         = self::MAIN_PHONE_1_RIGHT + 2.25;
    const MAIN_PHONE_2_RIGHT        = self::MAIN_PHONE_2_LEFT + 8.0;
    const MAIN_PHONE_3_LEFT         = self::MAIN_PHONE_2_RIGHT + 2.25;
    const MAIN_PHONE_3_RIGHT        = self::MAIN_PHONE_3_LEFT + 8.0;

    const SUB_LEFT                  = 180 - 55;
    const SUB_COMMON_LEFT           = self::SUB_LEFT + 6 + 5.08;
    const SUB_COMMON_RIGHT          = self::SUB_COMMON_LEFT + 5.08 * 8;
    const SUB_ACCOUNT_1_TOP         = 15;
    const SUB_ACCOUNT_1_BOTTOM      = self::SUB_ACCOUNT_1_TOP + 8;
    const SUB_ACCOUNT_2_TOP         = self::SUB_ACCOUNT_1_TOP;
    const SUB_ACCOUNT_2_BOTTOM      = self::SUB_ACCOUNT_1_BOTTOM;
    const SUB_ACCOUNT_3_TOP         = self::SUB_ACCOUNT_1_BOTTOM + 3;
    const SUB_ACCOUNT_3_BOTTOM      = self::SUB_ACCOUNT_3_TOP + 8;
    const SUB_ACCOUNT_1_LEFT        = self::SUB_COMMON_LEFT;
    const SUB_ACCOUNT_1_RIGHT       = self::SUB_COMMON_LEFT + 5.08 * 5;
    const SUB_ACCOUNT_2_LEFT        = self::SUB_ACCOUNT_1_RIGHT + 2.54;
    const SUB_ACCOUNT_2_RIGHT       = self::SUB_ACCOUNT_2_LEFT + 5.08;
    const SUB_ACCOUNT_3_LEFT        = self::SUB_COMMON_LEFT + 5.08;
    const SUB_ACCOUNT_3_RIGHT       = self::SUB_COMMON_RIGHT;
    const SUB_AMOUNT_TOP            = 15 + 8 + 3 + 8 + 10 + 3;
    const SUB_AMOUNT_BOTTOM         = self::SUB_AMOUNT_TOP + 8;
    const SUB_AMOUNT_LEFT           = self::SUB_COMMON_LEFT;
    const SUB_AMOUNT_RIGHT          = self::SUB_COMMON_RIGHT;
    const SUB_ACCOUNT_NAME_TOP      = self::SUB_ACCOUNT_3_BOTTOM + 1.75;
    const SUB_ACCOUNT_NAME_BOTTOM   = self::SUB_ACCOUNT_NAME_TOP - 1.75 + 10;
    const SUB_NAME_TOP              = self::SUB_AMOUNT_BOTTOM + 3.5;
    const SUB_NAME_BOTTOM           = self::SUB_AMOUNT_BOTTOM + 24 - 2;
    const SUB_NAME_LEFT             = self::SUB_COMMON_LEFT + 2.5;
    const SUB_NAME_RIGHT            = self::SUB_COMMON_RIGHT - 2;

    public $debug = false;
    public $fontNameJa = 'ipaexm';
    public $normalizeToWide = true;
    private $pdf;

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
    }

    public function render(): string
    {
        return $this->pdf->Output('', 'S');
    }

    public function setAccount(string $account1, string $account2, string $account3): self
    {
        // {{{
        $pad = function (string $number, string $padChar, int $length) : string {
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
        return $this
            ->drawAccountName(
                self::MAIN_ACCOUNT_NAME_LEFT,
                self::MAIN_ACCOUNT_NAME_TOP,
                self::MAIN_ACCOUNT_NAME_RIGHT,
                self::MAIN_ACCOUNT_NAME_BOTTOM,
                1.5, // padding
                $name
            )
            ->drawAccountName(
                self::SUB_COMMON_LEFT,
                self::SUB_ACCOUNT_NAME_TOP,
                self::SUB_COMMON_RIGHT,
                self::SUB_ACCOUNT_NAME_BOTTOM,
                1.0, // padding
                $name
            );
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
            static::pt2mm(12)
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
        string $phone3
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
        $this->drawName($name, $kana);
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
                function (string $text) : bool {
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
            $this->pdf->Rect($left, $top, $width, $height, 'D');
        }
        if ($paddingTop > 0) {
            $top = (float)number_format($top + $paddingTop, 2, '.', '');
            $height = (float)number_format($bottom - $top, 2, '.', '');
            if ($this->debug) {
                $this->pdf->Rect($left, $top, $width, $height, 'D');
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
        float $maxFontSize = 0
    ): self {
        // {{{
        if ($maxFontSize <= 0.1) {
            $maxFontSize = static::pt2mm(10.5);
        }
        $left = (float)number_format($left, 2, '.', '');
        $top = (float)number_format($top, 2, '.', '');
        $right = (float)number_format($right, 2, '.', '');
        $bottom = (float)number_format($bottom, 2, '.', '');
        $width = (float)number_format($right - $left, 2, '.', '');
        $height = (float)number_format($bottom - $top, 2, '.', '');
        if ($this->debug) {
            $this->pdf->Rect($left, $top, $width, $height, 'D');
        }
        $this->pdf->SetFont($this->fontNameJa, '', 0);
        $fontSize = $this->calcFontSize($text, $width, $height, $maxFontSize);
        $this->pdf->SetFont('', '', static::mm2pt($fontSize));
        list ($textWidth, $textHeight) = $this->calcTextSize($text);
        $this->pdf->SetXY(
            $left,
            $valign === 'T'
                ? $top
                : ($top + ($height / 2 - $textHeight / 2))
        );
        $this->pdf->MultiCell(
            0,
            $textHeight,
            $text,
            0,      // border
            'L',    // align
            false,  // fill
            0       // ln
        );
        return $this;
        // }}}
    }

    private function drawAccountName(
        float $left,
        float $top,
        float $right,
        float $bottom,
        float $padding,
        string $name
    ): self {
        // {{{
        $left   = (float)number_format($left, 2, '.', '');
        $top    = (float)number_format($top, 2, '.', '');
        $right  = (float)number_format($right, 2, '.', '');
        $bottom = (float)number_format($bottom, 2, '.', '');
        $width  = (float)number_format($right - $left, 2, '.', '');
        $height = (float)number_format($bottom - $top, 2, '.', '');
        $innerWidth  = (float)number_format($width - $padding * 2, 2, '.', '');
        $innerHeight = (float)number_format($height - $padding * 2, 2, '.', '');
        if ($this->debug) {
            $this->pdf->Rect($left, $top, $width, $height, 'D');
        }
        $this->pdf->SetFont($this->fontNameJa, '', 0);
        $fontSize = $this->calcFontSize($name, INF, $innerHeight);
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
                $fontSize = $this->calcFontSize($name, $innerWidth, $innerHeight);
                list ($textWidth, $textHeight) = $this->calcTextSize($name);
            }
        }

        $putLeft = ($left + $padding) + ($innerWidth / 2 - $textWidth / 2);
        $putTop = ($top + $padding) + ($innerHeight / 2 - $textHeight / 2);
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
        return $this;
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

    public function drawName(string $name, ?string $kana): self
    {
        // {{{
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
        if ($kana !== '') {
            $this->pdf->SetFont($this->fontNameJa, '', 0);
            $fontSize = $this->calcFontSize(
                $kana,
                (static::MAIN_NAME_RIGHT - static::MAIN_NAME_LEFT) / 0.75,
                $kanaMaxHeight + 0.8
            );
            $this->pdf->SetFont('', '', static::mm2pt($fontSize));
            list($textWidth, $textHeight) = $this->calcTextSize($kana);
            $this->drawAccountName(
                static::MAIN_NAME_LEFT,
                static::MAIN_NAME_TOP,
                static::MAIN_NAME_LEFT + $textWidth * 0.75,
                static::MAIN_NAME_TOP + $kanaMaxHeight + 0.8,
                0.0,
                $kana
            );
        }
        return $this;
        // }}}
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

    private function calcTextSize(string $text): array
    {
        // {{{
        $lines = explode("\n", $text);
        $this->pdf->SetXY(0, 0);
        return [
            // width
            max(array_map(
                function (string $text) : float {
                    return $this->pdf->GetStringWidth($text);
                },
                $lines
            )),
            // height
            array_reduce(
                $lines,
                function (float $carry, string $item) : float {
                    return $carry + $this->pdf->GetStringHeight(0, $item, false, 0);
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
