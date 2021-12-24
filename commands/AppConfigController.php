<?php

declare(strict_types=1);

namespace app\commands;

use CallbackFilterIterator;
use FilesystemIterator;
use SplFileInfo;
use Yii;
use yii\console\Controller;

final class AppConfigController extends Controller
{
    public function actionCookie(): int
    {
        $path = Yii::getAlias('@app/config/cookie.php');
        $value = file_exists($path)
            ? require($path)
            : Yii::$app->security->generateRandomString(32);

        echo implode("\n", [
            '<?php',
            '',
            'declare(strict_types=1);',
            '',
            'return (function (): string {',
            '    return "' . addslashes($value) . '";',
            '})();',
        ]) . "\n";

        return 0;
    }

    public function actionFavicon(): int
    {
        $srcPath = Yii::getAlias('@app/node_modules/@fetus-hina/fetus.css/dist/favicon');
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
        foreach ($it as $fileName => $info) {
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
                    if (preg_match('/^apple-touch-icon-(\d+)\.png$/', $fileName, $match)) {
                        $data[$fileName] = [
                            'rel' => 'apple-touch-icon',
                            'sizes' => sprintf('%1$dx%1$d', (int)$match[1]),
                            'type' => 'image/png',
                        ];
                    }
                    break;
            }
        }

        uksort($data, fn (string $a, string $b): int => strnatcasecmp($a, $b) ?: strcasecmp($a, $b) ?: strcmp($a, $b));
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
