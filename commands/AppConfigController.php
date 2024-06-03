<?php

declare(strict_types=1);

namespace app\commands;

use CallbackFilterIterator;
use Exception;
use FilesystemIterator;
use SplFileInfo;
use Yii;
use yii\console\Controller;

use function addslashes;
use function array_filter;
use function array_keys;
use function array_reduce;
use function copy;
use function file_exists;
use function fwrite;
use function implode;
use function is_string;
use function join;
use function preg_match;
use function sprintf;
use function str_replace;
use function strcasecmp;
use function strcmp;
use function strnatcasecmp;
use function uksort;

use const STDERR;

final class AppConfigController extends Controller
{
    public function actionCookie(): int
    {
        if (!$path = Yii::getAlias('@app/config/cookie.php')) {
            throw new Exception();
        }
        $value = file_exists($path)
            ? require($path)
            : Yii::$app->security->generateRandomString(32);

        $escValue = str_replace(
            ['\\', "'"],
            ['\\\\', "\\'"],
            $value,
        );

        echo implode("\n", [
            '<?php',
            '',
            'declare(strict_types=1);',
            '',
            "return '{$escValue}';",
        ]) . "\n";

        return 0;
    }

    public function actionFavicon(): int
    {
        if (!$srcPath = Yii::getAlias('@app/node_modules/@fetus-hina/fetus.css/dist/favicon')) {
            throw new Exception();
        }
        if (!file_exists($srcPath)) {
            fwrite(STDERR, "favicon path does not exists. node_modules not installed?\n");
            return 1;
        }

        $iconFiles = $this->findFavicons($srcPath);
        if (!$iconFiles) {
            fwrite(STDERR, "no favicon detected.\n");
            return 1;
        }

        foreach (array_keys($iconFiles) as $iconFileName) {
            if (
                !$this->setupFaviconFile(
                    $srcPath . '/' . $iconFileName,
                    Yii::getAlias('@app/web') . '/' . $iconFileName,
                )
            ) {
                fwrite(STDERR, "failed to set up $iconFileName\n");
                return 1;
            }
        }

        $view = $this->createFaviconLinkView($iconFiles);
        echo $view;

        return 0;
    }

    private function findFavicons(string $srcPath): array
    {
        $data = [];
        $it = new CallbackFilterIterator(
            new FilesystemIterator(
                $srcPath,
                array_reduce(
                    [
                        FilesystemIterator::CURRENT_AS_FILEINFO,
                        FilesystemIterator::KEY_AS_FILENAME,
                        FilesystemIterator::SKIP_DOTS,
                        FilesystemIterator::UNIX_PATHS,
                    ],
                    fn (int $a, int $b): int => $a | $b,
                    0,
                ),
            ),
            fn (SplFileInfo $info): bool => $info->isFile(),
        );
        foreach ($it as $fileName => $fileInfo) {
            unset($fileInfo);
            switch ($fileName) {
                case 'favicon.ico':
                    $data[$fileName] = [
                        'rel' => null,
                        'sizes' => null,
                        'type' => 'image/vnd.microsoft.icon',
                    ];
                    break;

                case 'favicon.svg':
                    $data[$fileName] = [
                        'rel' => 'icon',
                        'sizes' => 'any',
                        'type' => 'image/xml+svg',
                    ];
                    break;

                default:
                    if (
                        is_string($fileName) &&
                        preg_match('/^apple-touch-icon-(\d+)\.png$/', $fileName, $match)
                    ) {
                        $data[$fileName] = [
                            'rel' => 'apple-touch-icon',
                            'sizes' => sprintf('%1$dx%1$d', (int)$match[1]),
                            'type' => 'image/png',
                        ];
                    }
                    break;
            }
        }

        uksort(
            $data,
            fn ($a, $b): int => strnatcasecmp((string)$a, (string)$b)
                ?: strcasecmp((string)$a, (string)$b)
                ?: strcmp((string)$a, (string)$b),
        );
        return $data;
    }

    private function setupFaviconFile(string $srcPath, string $dstPath): bool
    {
        return @copy($srcPath, $dstPath);
    }

    private function createFaviconLinkView(array $files): string
    {
        $simpleAttrValue = fn (?string $v): ?string => $v === null ? null : "'" . addslashes($v) . "'";

        $lines = [];
        $lines[] = '<?php';
        $lines[] = '';
        $lines[] = 'declare(strict_types=1);';
        $lines[] = '';
        $lines[] = 'use yii\helpers\Html;';
        $lines[] = '';
        foreach ($files as $fileName => $data) {
            if (!isset($data['rel'])) {
                continue;
            }

            $attrs = array_filter([
                'href' => sprintf("Yii::getAlias('@web') . '/%s'", addslashes($fileName)),
                'rel' => $simpleAttrValue($data['rel']),
                'sizes' => $simpleAttrValue($data['sizes'] ?? null),
                'type' => $simpleAttrValue($data['type']),
            ]);

            $lines[] = "echo Html::tag('link', '', [";
            foreach ($attrs as $k => $v) {
                $lines[] = '  ' . $simpleAttrValue($k) . ' => ' . $v . ',';
            }
            $lines[] = ']) . "\n";';
        }

        return join("\n", $lines) . "\n";
    }
}
