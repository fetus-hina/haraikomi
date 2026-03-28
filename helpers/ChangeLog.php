<?php

declare(strict_types=1);

namespace app\helpers;

use Dom\HTMLDocument;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;
use Yii;
use cebe\markdown\GithubMarkdown;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;

use function checkdate;
use function file_exists;
use function file_get_contents;
use function hash;
use function is_array;
use function is_file;
use function is_readable;
use function is_string;
use function preg_match;
use function strcmp;
use function trim;
use function usort;

/**
 * @phpstan-type Entry array{date: non-empty-string, content: non-empty-string}
 */
final class ChangeLog
{
    public const IMPL_VERSION = 1;

    public static function getAnchorMapId(): string
    {
        return hash(
            'sha256',
            Json::encode(self::getAnchorMap()),
        );
    }

    private static function getAnchorMap(): array
    {
        return [
            '#bizudgothic' => 'https://github.com/googlefonts/morisawa-biz-ud-gothic',
            '#bizudmincho' => 'https://github.com/googlefonts/morisawa-biz-ud-mincho',
            '#genjyuu' => 'http://jikasei.me/font/genjyuu/',
            '#genshin' => 'http://jikasei.me/font/genshin/',
            '#ipaexgothic' => 'https://moji.or.jp/ipafont/',
            '#ipaexmincho' => 'https://moji.or.jp/ipafont/',
            '#ipagothic' => 'https://moji.or.jp/ipafont/',
            '#ipamincho' => 'https://moji.or.jp/ipafont/',
            '#jetbrainsmono' => 'https://www.jetbrains.com/ja-jp/lp/mono/',
            '#mikachan' => 'http://mikachan-font.com/',
            '#mplus1p' => 'https://mplusfonts.github.io/',
            '#nyashi' => 'http://marusexijaxs2.web.fc2.com/',
            '#roboto' => 'https://fonts.google.com/specimen/Roboto',
            '#umefont' => 'https://ja.osdn.net/projects/ume-font/wiki/FrontPage',
        ];
    }

    /**
     * @phpstan-return Entry[]
     */
    public static function loadFile(string $path): array
    {
        $path = (string)Yii::getAlias($path);
        if (!file_exists($path) || !is_file($path) || !is_readable($path)) {
            throw new InvalidArgumentException();
        }

        $yaml = @file_get_contents($path);
        if (!is_string($yaml)) {
            throw new InvalidArgumentException();
        }

        return self::loadYaml($yaml);
    }

    /**
     * @phpstan-return Entry[]
     */
    public static function loadYaml(string $yaml)
    {
        $yamlData = Yaml::parse(trim($yaml));
        if (!is_array($yamlData)) {
            throw new InvalidArgumentException();
        }

        $results = [];
        foreach ($yamlData as $dateStr => $contentMdStr) {
            if (
                !is_string($dateStr) ||
                $dateStr === '' ||
                !self::validateDate($dateStr)
            ) {
                throw new RuntimeException("{$dateStr} is not a valid date");
            }

            if (!$contentFormatted = self::convertContent($contentMdStr)) {
                throw new RuntimeException("Markdown content for {$dateStr} is invalid");
            }

            $results[] = [
                'date' => $dateStr,
                'content' => $contentFormatted,
            ];
        }
        usort(
            $results,
            fn (array $a, array $b): int => strcmp($b['date'], $a['date'])
                ?: strcmp($a['content'], $b['content']),
        );
        return $results;
    }

    private static function validateDate(string $date): bool
    {
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $match)) {
            return false;
        }

        return checkdate(
            (int)$match[2], // month
            (int)$match[3], // mday
            (int)$match[1], // year
        );
    }

    private static function convertContent(string $markdown): ?string
    {
        if (($html = self::parseMarkdown($markdown)) === null) {
            return null;
        }

        return self::parseAndRewriteAnchors($html);
    }

    private static function parseMarkdown(string $markdown): ?string
    {
        $parser = new GithubMarkdown();
        $parser->enableNewlines = true;
        $parser->html5 = true;
        $parser->keepListStartNumber = false;
        $html = $parser->parse($markdown);
        return $html !== '' ? $html : null;
    }

    private static function parseAndRewriteAnchors(string $html): string
    {
        $doc = HTMLDocument::createFromString(
            "<div>{$html}</div>",
            LIBXML_NOERROR,
        );
        self::rewriteAnchors($doc);

        $div = $doc->querySelector('body > div');
        if (!$div || !$generated = $doc->saveHtml($div)) {
            throw new RuntimeException();
        }
        return $generated;
    }

    private static function rewriteAnchors(HTMLDocument $doc): void
    {
        $map = self::getAnchorMap();
        foreach ($doc->querySelectorAll('a[href^="#"]') as $anchor) {
            $href = $anchor->getAttribute('href');
            if (!isset($map[$href])) {
                throw new RuntimeException("{$href} is not known");
            }
            $anchor->setAttribute('href', $map[$href]);
        }

        foreach ($doc->querySelectorAll('a[href]') as $anchor) {
            $href = $anchor->getAttribute('href');
            if (preg_match('#^https?://#i', $href)) {
                $anchor->setAttribute('rel', 'noreferrer noopener');
                $anchor->setAttribute('target', '_blank');
            }
        }
    }
}
