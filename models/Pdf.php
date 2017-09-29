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
    const MAIN_ACCOUNT_NAME_TOP     = self::MAIN_ACCOUNT_BOTTOM;
    const MAIN_ACCOUNT_NAME_BOTTOM  = self::MAIN_ACCOUNT_NAME_TOP + 10;
    const MAIN_ACCOUNT_NAME_LEFT    = self::MAIN_ACCOUNT_1_LEFT + 5.08;
    const MAIN_ACCOUNT_NAME_RIGHT   = self::MAIN_ACCOUNT_3_RIGHT;

    const MAIN_POSTALCODE_MIDDLE    = 60.2;
    const MAIN_POSTALCODE_1_LEFT    = 15.0;
    const MAIN_POSTALCODE_1_RIGHT   = 22.0;
    const MAIN_POSTALCODE_2_LEFT    = 25.5;
    const MAIN_POSTALCODE_2_RIGHT   = self::MAIN_AMOUNT_RIGHT;

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
    const SUB_ACCOUNT_NAME_TOP      = self::SUB_ACCOUNT_3_BOTTOM;
    const SUB_ACCOUNT_NAME_BOTTOM   = self::SUB_ACCOUNT_NAME_TOP + 10;

    public $debug = false;
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

    public function render() : string
    {
        return $this->pdf->Output('', 'S');
    }

    public function setAccount(string $account1, string $account2, string $account3) : self
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

    public function setAmount(string $amount) : self
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

    public function setAccountName(string $name) : self
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

    public function setPostalCode(string $code) : self
    {
        $code1 = substr($code, 0, 3);
        $code2 = substr($code, 3, 4);
       
        // main {{{
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
        // }}}
        return $this;
    }

    public function setPhone(string $phone1, string $phone2, string $phone3) : self
    {
        // main {{{
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
        // }}}

        return $this;
    }

    private function drawNumbersToCells(
        float $left,
        float $top,
        float $right,
        float $bottom,
        string $numbers,
        float $fontSize = 4.5) : self
    {
        // {{{
        $left = (float)number_format($left, 2, '.', '');
        $top = (float)number_format($top, 2, '.', '');
        $right = (float)number_format($right, 2, '.', '');
        $bottom = (float)number_format($bottom, 2, '.', '');
        $width = (float)number_format($right - $left, 2, '.', '');
        $height = (float)number_format($bottom - $top, 2, '.', '');
        if ($this->debug) {
            $this->pdf->Rect($left, $top, $width, $height, 'D');
            Yii::warning('Debug rect: ' . json_encode([
                'left' => $left,
                'top' => $top,
                'width' => $width,
                'height' => $height,
            ]));
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
                Yii::warning('Put char ' . $char . ' to ' . ($left + $widthPerChar * $i) . ', ' . ($top + ($height / 2 - $numbersHeight / 2)));
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

    private function drawAccountName(
        float $left,
        float $top,
        float $right,
        float $bottom,
        float $padding,
        string $name) : self
    {
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
        $this->pdf->SetFont('ipaexm', '', 0);
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

    private function calcTextSize(string $text) : array
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
        float $minFontSize = 0.1) : float
    {
        // {{{
        for ($i = 0; ; ++$i) {
            $fontSize = (float)number_format($maxFontSize - 0.1 * $i, 2, '.', '');
            if ($fontSize <= $minFontSize || $fontSize <= 0) {
                return $minFontSize;
            }
            $this->pdf->SetFont('', '', static::mm2pt($fontSize));
            list($textWidth, $textHeight) = $this->calcTextSize($text);
            // Yii::warning(
            //     'calcFontSize: ' . json_encode([
            //         'text'          => $text,
            //         'width'         => is_infinite($width) ? 'INF' : $width,
            //         'height'        => is_infinite($height) ? 'INF' : $height,
            //         'fontSize'      => $fontSize,
            //         'textWidth'     => $textWidth,
            //         'textHeight'    => $textHeight,
            //     ])
            // );
            if ($textWidth <= $width && $textHeight <= $height) {
                return $fontSize;
            }
        }
        // }}}
    }

    private static function mm2pt(float $mm) : float
    {
        return $mm * 72.0 / 25.4;
    }
}
