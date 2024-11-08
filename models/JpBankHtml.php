<?php

declare(strict_types=1);

namespace app\models;

use DOMElement;
use DOMNodeList;
use DOMXPath;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Generator;
use Masterminds\HTML5;
use Normalizer;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Yii;
use yii\base\Model;

use function array_shift;
use function count;
use function implode;
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

        $cssSelConv = new CssSelectorConverter();

        $html5 = new HTML5(['disable_html_ns' => true]);
        $doc = $html5->loadHTML((string)$htmlContent);
        $xpath = new DOMXPath($doc);

        $query = $cssSelConv->toXPath(implode(', ', [
            'tr',
        ]));

        $disaster = null;
        $disasterRemains = 0;
        foreach (self::expectNodeList($xpath->query($query)) as $row) {
            $tds = iterator_to_array(
                self::expectNodeList(
                    $xpath->query(
                        './td',
                        self::expectXmlElement($row),
                    ),
                ),
            );
            if (count($tds) === 0) {
                // たぶんヘッダ行
                continue;
            }

            if (count($tds) === 5) {
                if ($disasterRemains > 0) {
                    throw new Exception('災害名のセル結合の消費が尽きる前に 5 セル現れた'); // @codeCoverageIgnore
                }

                $td = self::expectXmlElement(array_shift($tds));
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
                    '/（使用可能な略称.*?）$/u',
                    '',
                    self::expectXmlElement($tds[0])->textContent,
                ),
            );
            $account = $this->normalizeText(
                self::expectXmlElement($tds[2])->textContent,
            );
            $term = $this->normalizeText(
                self::expectXmlElement($tds[3])->textContent,
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

    private static function expectNodeList(DOMNodeList|false $list): DOMNodeList
    {
        return $list instanceof DOMNodeList
            ? $list
            : throw new Exception('Xpath query filed');
    }

    private static function expectXmlElement(mixed $node): DOMElement
    {
        return $node instanceof DOMElement
            ? $node
            : throw new Exception('The node is not an element');
    }
}
