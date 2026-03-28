<?php

declare(strict_types=1);

namespace app\models;

use DateTimeImmutable;
use DateTimeZone;
use Dom\Element;
use Dom\HTMLDocument;
use Exception;
use Generator;
use Normalizer;
use Yii;
use yii\base\Model;

use function array_filter;
use function array_shift;
use function array_values;
use function count;
use function iterator_to_array;
use function preg_match;
use function preg_replace;
use function strlen;
use function trim;

final class JpBankHtml extends Model
{
    public string $html;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['html'], 'required'],
            [['html'], 'string'],
        ];
    }

    /** @return Generator<int, JpBankHtmlAccount> */
    public function parse(): Generator
    {
        // 同じ災害の 2 つ目以降の <tr> が省略されている糞 HTML が喰わされる
        $htmlContent = preg_replace('#(</tr>)\s*(<td)#s', '\1<tr>\2', $this->html);

        $doc = HTMLDocument::createFromString(
            (string)$htmlContent,
            LIBXML_NOERROR,
        );

        $disaster = null;
        $disasterRemains = 0;
        foreach ($doc->querySelectorAll('tr') as $row) {
            $tds = array_values(array_filter(
                iterator_to_array($row->childNodes),
                fn (mixed $node): bool => $node instanceof Element && $node->localName === 'td',
            ));
            if (count($tds) === 0) {
                // たぶんヘッダ行
                continue;
            }

            if (count($tds) === 5) {
                if ($disasterRemains > 0) {
                    throw new Exception('災害名のセル結合の消費が尽きる前に 5 セル現れた'); // @codeCoverageIgnore
                }

                $td = self::expectElement(array_shift($tds));
                $disaster = $this->normalizeText($td->textContent);
                $disasterRemains = (int)$td->getAttribute('rowspan');
                if ($disasterRemains === 0) {
                    $disasterRemains = 1;
                }
            }

            if (count($tds) !== 4) {
                throw new Exception('セルの数が異常: ' . count($tds) . ' expect 4'); // @codeCoverageIgnore
            }

            if ($disaster === null) {
                throw new Exception('$disaster is null');
            }

            if ($disasterRemains < 1) {
                throw new Exception('$disasterRemains < 1'); // @codeCoverageIgnore
            }

            --$disasterRemains;
            $accountName = $this->normalizeText(
                (string)preg_replace(
                    '/[（(]使用可能な略称.*?[）)]\s*$/u',
                    '',
                    self::expectElement($tds[0])->textContent,
                ),
            );
            $account = $this->normalizeText(
                self::expectElement($tds[2])->textContent,
            );
            $term = $this->normalizeText(
                self::expectElement($tds[3])->textContent,
            );

            if (
                strlen($accountName) > 0 &&
                preg_match('/^(\d{5})-(\d)-(\d{1,6})/', $account, $aMatch) &&
                preg_match('/^(\d{4})\D+(\d{1,2})\D+(\d{1,2})\D+(\d{4})\D+(\d{1,2})\D+(\d{1,2})$/', $term, $tMatch)
            ) {
                yield Yii::createObject([
                    'class' => JpBankHtmlAccount::class,
                    'disaster' => $disaster,
                    'accountName' => $accountName,
                    'account' => [
                        (int)$aMatch[1],
                        (int)$aMatch[2],
                        (int)$aMatch[3],
                    ],
                    'start' => (new DateTimeImmutable())
                        ->setTimezone(new DateTimeZone('Asia/Tokyo'))
                        ->setDate((int)$tMatch[1], (int)$tMatch[2], (int)$tMatch[3])
                        ->setTime(0, 0, 0),
                    'end' => (new DateTimeImmutable())
                        ->setTimezone(new DateTimeZone('Asia/Tokyo'))
                        ->setDate((int)$tMatch[4], (int)$tMatch[5], (int)$tMatch[6] + 1)
                        ->setTime(0, 0, -1),
                ]);
            } else {
                throw new Exception('Unmatch'); // @codeCoverageIgnore
            }
        }
    }

    private function normalizeText(string $text): string
    {
        if (($text = Normalizer::normalize($text, Normalizer::FORM_C)) === false) {
            throw new Exception('Failed to normalize text'); // @codeCoverageIgnore
        }
        $text = (string)preg_replace('/\s+/s', ' ', $text);
        $text = trim($text);
        return $text;
    }

    private static function expectElement(mixed $node): Element
    {
        return $node instanceof Element
            ? $node
            : throw new Exception('The node is not an element');
    }
}
