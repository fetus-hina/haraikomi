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

    private static function mm2pt(float $mm) : float
    {
        return $mm * 72.0 / 25.4;
    }
}
