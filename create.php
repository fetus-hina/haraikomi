<?php
define('K_PATH_FONTS', __DIR__ . '/fonts/_tcpdf/');

require_once(__DIR__ . '/vendor/autoload.php');

function mm2pt(float $mm) : float
{
    return $mm * 72.0 / 25.4;
}

function pt2mm(float $pt) : float
{
    return $mm * 25.4 / 72.0;
}

function cellNumber(TCPDF $pdf, float $left, float $top, float $width, float $height, string $text) : void
{
    if ($text == '') {
        return;
    }

    $widthPerChar = $width / strlen($text);
    $pdf->SetXY(0, 0);
    $textHeight = $pdf->GetStringHeight(0, $text, false, 0);
    for ($i = 0; $i < strlen($text); ++$i)
    {
        $char = substr($text, $i, 1);
        if ($char === ' ') {
            continue;
        }
        $pdf->SetXY(
            $left + $widthPerChar * $i,
            $top + ($height / 2 - $textHeight / 2)
        );
        $pdf->Cell(
            $widthPerChar,
            $textHeight,
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

define('MAIN_ACCOUNT_TOP', 6 + 6 + 3);
define('MAIN_ACCOUNT_BOTTOM', MAIN_ACCOUNT_TOP + 8);
define('MAIN_ACCOUNT_1_LEFT', 4);
define('MAIN_ACCOUNT_1_RIGHT', MAIN_ACCOUNT_1_LEFT + 5.08 * 5);
define('MAIN_ACCOUNT_2_LEFT', MAIN_ACCOUNT_1_RIGHT + 2.54);
define('MAIN_ACCOUNT_2_RIGHT', MAIN_ACCOUNT_2_LEFT + 5.08);
define('MAIN_ACCOUNT_3_LEFT', MAIN_ACCOUNT_2_RIGHT + 2.54);
define('MAIN_ACCOUNT_3_RIGHT', MAIN_ACCOUNT_3_LEFT + 5.08 * 7);
define('MAIN_PRICE_TOP', MAIN_ACCOUNT_TOP);
define('MAIN_PRICE_BOTTOM', MAIN_ACCOUNT_BOTTOM);
define('MAIN_PRICE_LEFT', MAIN_ACCOUNT_3_RIGHT + 5.08);
define('MAIN_PRICE_RIGHT', MAIN_PRICE_LEFT + 5.08 * 8);
define('MAIN_DST_NAME_TOP', 6 + 6 + 3 + 8);
define('MAIN_DST_NAME_BOTTOM', MAIN_DST_NAME_TOP + 10);
define('MAIN_DST_NAME_LEFT', 4 + 5.08);
define('MAIN_DST_NAME_RIGHT', MAIN_ACCOUNT_3_RIGHT);
define('MAIN_FROM_NAME_TOP', 63);
define('MAIN_FROM_NAME_BOTTOM', 90 - 5);
define('MAIN_FROM_NAME_LEFT', MAIN_DST_NAME_LEFT + 5);
define('MAIN_FROM_NAME_RIGHT', 180 - (55 + 4.16 + 30 + 5));
define('MAIN_NOTE_TOP', MAIN_DST_NAME_BOTTOM);
define('MAIN_NOTE_BOTTOM', MAIN_FROM_NAME_TOP);
define('MAIN_NOTE_LEFT', MAIN_DST_NAME_LEFT);
define('MAIN_NOTE_RIGHT', MAIN_PRICE_RIGHT);

define('SUB_LEFT', 180 - 55);
define('SUB_COMMON_LEFT', SUB_LEFT + 6 + 5.08);
define('SUB_COMMON_RIGHT', SUB_COMMON_LEFT + 5.08 * 8);
define('SUB_ACCOUNT_1_TOP', 15);
define('SUB_ACCOUNT_1_BOTTOM', 15 + 8);
define('SUB_ACCOUNT_2_TOP', SUB_ACCOUNT_1_TOP);
define('SUB_ACCOUNT_2_BOTTOM', SUB_ACCOUNT_1_BOTTOM);
define('SUB_ACCOUNT_3_TOP', 15 + 8 + 3);
define('SUB_ACCOUNT_3_BOTTOM', SUB_ACCOUNT_3_TOP + 8);
define('SUB_ACCOUNT_1_LEFT', SUB_COMMON_LEFT);
define('SUB_ACCOUNT_1_RIGHT', SUB_COMMON_LEFT + 5.08 * 5);
define('SUB_ACCOUNT_2_LEFT', SUB_ACCOUNT_1_RIGHT + 2.54);
define('SUB_ACCOUNT_2_RIGHT', SUB_ACCOUNT_2_LEFT + 5.08);
define('SUB_ACCOUNT_3_LEFT', SUB_COMMON_LEFT + 5.08 * 2);
define('SUB_ACCOUNT_3_RIGHT', SUB_COMMON_RIGHT);
define('SUB_PRICE_TOP', 15 + 8 + 3 + 8 + 10 + 3);
define('SUB_PRICE_BOTTOM', SUB_PRICE_TOP + 8);
define('SUB_DST_NAME_TOP', 15 + 8 + 3 + 8);
define('SUB_DST_NAME_BOTTOM', SUB_DST_NAME_TOP + 10);
define('SUB_FROM_NAME_TOP', 15 + 8 + 3 + 8 + 10 + 3 + 8);
define('SUB_FROM_NAME_BOTTOM', SUB_FROM_NAME_TOP + 24);


$data = require(__DIR__ . '/data.php');

$pdf = new TCPDF('L', 'mm', [114, 180], true, 'UTF-8');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetMargins(0, 0, 0);
$pdf->SetCellPadding(0);
$pdf->SetAutoPageBreak(false, 0);

$pdf->AddPage();

if (false) {
    $pdf->Rect(MAIN_ACCOUNT_1_LEFT, MAIN_ACCOUNT_TOP, MAIN_ACCOUNT_1_RIGHT - MAIN_ACCOUNT_1_LEFT, MAIN_ACCOUNT_BOTTOM - MAIN_ACCOUNT_TOP, 'D');
    $pdf->Rect(MAIN_ACCOUNT_2_LEFT, MAIN_ACCOUNT_TOP, MAIN_ACCOUNT_2_RIGHT - MAIN_ACCOUNT_2_LEFT, MAIN_ACCOUNT_BOTTOM - MAIN_ACCOUNT_TOP, 'D');
    $pdf->Rect(MAIN_ACCOUNT_3_LEFT, MAIN_ACCOUNT_TOP, MAIN_ACCOUNT_3_RIGHT - MAIN_ACCOUNT_3_LEFT, MAIN_ACCOUNT_BOTTOM - MAIN_ACCOUNT_TOP, 'D');
    $pdf->Rect(MAIN_PRICE_LEFT, MAIN_PRICE_TOP, MAIN_PRICE_RIGHT - MAIN_PRICE_LEFT, MAIN_PRICE_BOTTOM - MAIN_PRICE_TOP, 'D');
    $pdf->Rect(MAIN_DST_NAME_LEFT, MAIN_DST_NAME_TOP, MAIN_DST_NAME_RIGHT - MAIN_DST_NAME_LEFT, MAIN_DST_NAME_BOTTOM - MAIN_DST_NAME_TOP, 'D');
    $pdf->Rect(MAIN_FROM_NAME_LEFT, MAIN_FROM_NAME_TOP, MAIN_FROM_NAME_RIGHT - MAIN_FROM_NAME_LEFT, MAIN_FROM_NAME_BOTTOM - MAIN_FROM_NAME_TOP, 'D');
    $pdf->Rect(MAIN_NOTE_LEFT, MAIN_NOTE_TOP, MAIN_NOTE_RIGHT - MAIN_NOTE_LEFT, MAIN_NOTE_BOTTOM - MAIN_NOTE_TOP, 'D');
    
    $pdf->Rect(SUB_ACCOUNT_1_LEFT, SUB_ACCOUNT_1_TOP, SUB_ACCOUNT_1_RIGHT - SUB_ACCOUNT_1_LEFT, SUB_ACCOUNT_1_BOTTOM - SUB_ACCOUNT_1_TOP, 'D');
    $pdf->Rect(SUB_ACCOUNT_2_LEFT, SUB_ACCOUNT_2_TOP, SUB_ACCOUNT_2_RIGHT - SUB_ACCOUNT_2_LEFT, SUB_ACCOUNT_2_BOTTOM - SUB_ACCOUNT_2_TOP, 'D');
    $pdf->Rect(SUB_ACCOUNT_3_LEFT, SUB_ACCOUNT_3_TOP, SUB_ACCOUNT_3_RIGHT - SUB_ACCOUNT_3_LEFT, SUB_ACCOUNT_3_BOTTOM - SUB_ACCOUNT_3_TOP, 'D');
    $pdf->Rect(SUB_COMMON_LEFT, SUB_PRICE_TOP, SUB_COMMON_RIGHT - SUB_COMMON_LEFT, SUB_PRICE_BOTTOM - SUB_PRICE_TOP, 'D');
    $pdf->Rect(SUB_COMMON_LEFT, SUB_DST_NAME_TOP, SUB_COMMON_RIGHT - SUB_COMMON_LEFT, SUB_DST_NAME_BOTTOM - SUB_DST_NAME_TOP, 'D');
    $pdf->Rect(SUB_COMMON_LEFT, SUB_FROM_NAME_TOP, SUB_COMMON_RIGHT - SUB_COMMON_LEFT, SUB_FROM_NAME_BOTTOM - SUB_FROM_NAME_TOP, 'D');
}


// 番号（メイン） {{{
$pdf->SetFont('ocrb_aizu_1_1', '', mm2pt(7));
cellNumber(
    $pdf,
    MAIN_ACCOUNT_1_LEFT,
    MAIN_ACCOUNT_TOP,
    MAIN_ACCOUNT_1_RIGHT - MAIN_ACCOUNT_1_LEFT,
    MAIN_ACCOUNT_BOTTOM - MAIN_ACCOUNT_TOP,
    substr('00000' . $data['dst']['number1'], -5)
);
cellNumber(
    $pdf,
    MAIN_ACCOUNT_2_LEFT,
    MAIN_ACCOUNT_TOP,
    MAIN_ACCOUNT_2_RIGHT - MAIN_ACCOUNT_2_LEFT,
    MAIN_ACCOUNT_BOTTOM - MAIN_ACCOUNT_TOP,
    $data['dst']['number2']
);
cellNumber(
    $pdf,
    MAIN_ACCOUNT_3_LEFT,
    MAIN_ACCOUNT_TOP,
    MAIN_ACCOUNT_3_RIGHT - MAIN_ACCOUNT_3_LEFT,
    MAIN_ACCOUNT_BOTTOM - MAIN_ACCOUNT_TOP,
    substr(
        str_repeat(' ', 7) . ltrim($data['dst']['number3'], '0'),
        -7
    )
);
// }}}
// 金額（メイン） {{{
$pdf->SetFont('ocrb_aizu_1_1', '', mm2pt(7));
cellNumber(
    $pdf,
    MAIN_PRICE_LEFT,
    MAIN_PRICE_TOP,
    MAIN_PRICE_RIGHT - MAIN_PRICE_LEFT,
    MAIN_PRICE_BOTTOM - MAIN_PRICE_TOP,
    substr(
        str_repeat(' ', 8) . ltrim((string)$data['price'], '0'),
        -8
    )
);
// }}}
// 番号（サブ） {{{
$pdf->SetFont('ocrb_aizu_1_1', '', mm2pt(7));
cellNumber(
    $pdf,
    SUB_ACCOUNT_1_LEFT,
    SUB_ACCOUNT_1_TOP,
    SUB_ACCOUNT_1_RIGHT - SUB_ACCOUNT_1_LEFT,
    SUB_ACCOUNT_1_BOTTOM - SUB_ACCOUNT_1_TOP,
    substr('00000' . $data['dst']['number1'], -5)
);
cellNumber(
    $pdf,
    SUB_ACCOUNT_2_LEFT,
    SUB_ACCOUNT_2_TOP,
    SUB_ACCOUNT_2_RIGHT - SUB_ACCOUNT_2_LEFT,
    SUB_ACCOUNT_2_BOTTOM - SUB_ACCOUNT_2_TOP,
    $data['dst']['number2']
);
cellNumber(
    $pdf,
    SUB_ACCOUNT_3_LEFT,
    SUB_ACCOUNT_3_TOP,
    SUB_ACCOUNT_3_RIGHT - SUB_ACCOUNT_3_LEFT,
    SUB_ACCOUNT_3_BOTTOM - SUB_ACCOUNT_3_TOP,
    substr(
        str_repeat(' ', 6) . ltrim($data['dst']['number3'], '0'),
        -6
    )
);
// }}}
// 金額（サブ） {{{
$pdf->SetFont('ocrb_aizu_1_1', '', mm2pt(7));
cellNumber(
    $pdf,
    SUB_COMMON_LEFT,
    SUB_PRICE_TOP,
    SUB_COMMON_RIGHT - SUB_COMMON_LEFT,
    SUB_PRICE_BOTTOM - SUB_PRICE_TOP,
    substr(
        str_repeat(' ', 8) . ltrim((string)$data['price'], '0'),
        -8
    )
);
// }}}

// 加入者名（メイン） {{{
$text = trim($data['dst']['name']);
for ($fontSize = 7; $fontSize > 0; $fontSize -= 0.125) {
    $pdf->SetFont('ipaexm', '', mm2pt($fontSize));
    $pdf->SetXY(0, 0);
    $textHeight = $pdf->GetStringHeight(0, $text, false, 0);
    $textWidth = $pdf->GetStringWidth($text);
    $width = MAIN_DST_NAME_RIGHT - MAIN_DST_NAME_LEFT;
    $height = MAIN_DST_NAME_BOTTOM - MAIN_DST_NAME_TOP;
    if ($width - 4 >= $textWidth) {
        $pdf->SetXY(
            MAIN_DST_NAME_LEFT,
            MAIN_DST_NAME_TOP + ($height / 2 - $textHeight / 2)
        );
        $pdf->Cell(
            $width,
            $textHeight,
            $text,
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
        break;
    }
}
// }}}
// 加入者名（サブ） {{{
$text = trim($data['dst']['name']);
for ($fontSize = 7; $fontSize > 0; $fontSize -= 0.125) {
    $pdf->SetFont('ipaexm', '', mm2pt($fontSize));
    $pdf->SetXY(0, 0);
    $textHeight = $pdf->GetStringHeight(0, $text, false, 0);
    $textWidth = $pdf->GetStringWidth($text);
    $width = SUB_COMMON_RIGHT - SUB_COMMON_LEFT;
    $height = SUB_DST_NAME_BOTTOM - SUB_DST_NAME_TOP;
    if ($width - 2 >= $textWidth) {
        $pdf->SetXY(
            SUB_COMMON_LEFT,
            SUB_DST_NAME_TOP + ($height / 2 - $textHeight / 2)
        );
        $pdf->Cell(
            $width,
            $textHeight,
            $text,
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
        break;
    }
}
// }}}

// 依頼人（メイン） {{{
$text = mb_convert_kana(
    implode("\n", [
        sprintf('〒%03d-%04d', $data['src']['postal_number1'], $data['src']['postal_number2']),
        $data['src']['address'],
        $data['src']['name'],
        implode('-', [
            $data['src']['phone1'],
            $data['src']['phone2'],
            $data['src']['phone3'],
        ]),
    ]),
    'ASKV',
    'UTF-8'
);
for ($fontSize = 5; $fontSize > 0; $fontSize -= 0.125) {
    $pdf->SetFont('ipaexm', '', mm2pt($fontSize));
    $pdf->SetXY(0, 0);
    $textHeight = array_reduce(
        explode("\n", $text),
        function ($carry, $item) use ($pdf) {
            return $carry + $pdf->GetStringHeight(0, $item, false, 0);
        },
        0
    );
    $textWidth = max(
        array_map(
            function ($text) use ($pdf) {
                return $pdf->GetStringWidth($text);
            },
            explode("\n", $text)
        )
    );
    $width = MAIN_FROM_NAME_RIGHT - MAIN_FROM_NAME_LEFT;
    $height = MAIN_FROM_NAME_BOTTOM - MAIN_FROM_NAME_TOP;
    if ($width - 2 >= $textWidth && $height - 2 >= $textHeight) {
        $pdf->SetXY(
            MAIN_FROM_NAME_LEFT + 1,
            MAIN_FROM_NAME_TOP + ($height / 2 - $textHeight / 2)
        );
        $pdf->MultiCell(
            $width,
            $textHeight,
            $text,
            0,      // border
            'L',    // align
            false  // fill
        );
        break;
    }
}
// }}}
// 依頼人（サブ） {{{
$text = mb_convert_kana(
    implode("\n", [
        sprintf('〒%03d-%04d', $data['src']['postal_number1'], $data['src']['postal_number2']),
        $data['src']['address2'],
        '',
        $data['src']['name'],
    ]),
    'ASKV',
    'UTF-8'
);
for ($fontSize = 5; $fontSize > 0; $fontSize -= 0.125) {
    $pdf->SetFont('ipaexm', '', mm2pt($fontSize));
    $pdf->SetXY(0, 0);
    $textHeight = array_reduce(
        explode("\n", $text),
        function ($carry, $item) use ($pdf) {
            return $carry + $pdf->GetStringHeight(0, $item, false, 0);
        },
        0
    );
    $textWidth = max(
        array_map(
            function ($text) use ($pdf) {
                return $pdf->GetStringWidth($text);
            },
            explode("\n", $text)
        )
    );
    $width = SUB_COMMON_RIGHT - SUB_COMMON_LEFT;
    $height = SUB_FROM_NAME_BOTTOM - SUB_FROM_NAME_TOP;
    if ($width - 2 >= $textWidth && $height - 2 >= $textHeight) {
        $pdf->SetXY(
            SUB_COMMON_LEFT + 1,
            SUB_FROM_NAME_TOP + ($height / 2 - $textHeight / 2)
        );
        $pdf->MultiCell(
            $width,
            $textHeight,
            $text,
            0,      // border
            'L',    // align
            false  // fill
        );
        break;
    }
}
// }}}

// 通信欄（メイン） {{{
$text = $data['note'];
for ($fontSize = 5; $fontSize > 0; $fontSize -= 0.125) {
    $pdf->SetFont('ipaexm', '', mm2pt($fontSize));
    $pdf->SetXY(0, 0);
    $textHeight = array_reduce(
        explode("\n", $text),
        function ($carry, $item) use ($pdf) {
            return $carry + $pdf->GetStringHeight(0, $item, false, 0);
        },
        0
    );
    $textWidth = max(
        array_map(
            function ($text) use ($pdf) {
                return $pdf->GetStringWidth($text);
            },
            explode("\n", $text)
        )
    );
    $width = MAIN_NOTE_RIGHT - MAIN_NOTE_LEFT;
    $height = MAIN_NOTE_BOTTOM - MAIN_NOTE_TOP;
    if ($width - 4 >= $textWidth && $height - 2 >= $textHeight) {
        $pdf->SetXY(
            MAIN_NOTE_LEFT + 2,
            MAIN_NOTE_TOP + 1 + ($height / 2 - $textHeight / 2)
        );
        $pdf->MultiCell(
            $width,
            $textHeight,
            $text,
            0,      // border
            'L',    // align
            false  // fill
        );
        break;
    }
}
// }}}

$pdf->Output(__DIR__ . '/result.pdf', 'F');
