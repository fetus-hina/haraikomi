<?php

declare(strict_types=1);

namespace app\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

use function implode;
use function preg_match;
use function trim;

/**
 * @phpstan-import-type Entry from \app\helpers\ChangeLog
 */
final class ChangeLogEntry extends Widget
{
    /** @phpstan-var Entry|null $entry */
    public ?array $entry = null;

    public function run(): string
    {
        if (!$entry = $this->entry) {
            throw new InvalidConfigException();
        }

        return implode('', [
            $this->renderDate($entry['date']),
            $this->renderContent($entry['content']),
        ]);
    }

    private function renderDate(string $dateStr): string
    {
        if (!preg_match('/^\d+-\d+-\d+$/', $dateStr)) {
            throw new InvalidConfigException();
        }

        return Html::tag(
            'time',
            Html::encode(
                Yii::$app->formatter->asDate($dateStr, 'long'),
            ),
            [
                'datetime' => $dateStr,
            ],
        );
    }

    private function renderContent(string $html): string
    {
        return trim($html);
    }
}
