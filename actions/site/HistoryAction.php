<?php

declare(strict_types=1);

namespace app\actions\site;

use Yii;
use app\helpers\ChangeLog;
use yii\base\Action;
use yii\base\InvalidConfigException;

/**
 * @phpstan-import-type Entry from ChangeLog as ChangeLogEntry
 */
final class HistoryAction extends Action
{
    public const CACHE_DURATION = 30 * 86400;
    public const CACHE_VERSION = 1;
    public const CHANGE_LOG_PATH = '@app/changelog.yml';

    public function run(): string
    {
        return $this->controller->render('history', [
            'entries' => $this->loadChangeLog(),
        ]);
    }

    /**
     * @phpstan-return ChangeLogEntry[]
     */
    private function loadChangeLog(): array
    {
        if (YII_ENV_DEV) {
            return ChangeLog::loadFile(self::CHANGE_LOG_PATH);
        }

        return Yii::$app->cache->getOrSet(
            $this->getCacheId(),
            fn () => ChangeLog::loadFile(self::CHANGE_LOG_PATH),
            self::CACHE_DURATION,
        );
    }

    private function getCacheId(): string
    {
        $path = Yii::getAlias(self::CHANGE_LOG_PATH);
        if (!is_string($path) || !file_exists($path) || !is_readable($path)) {
            throw new InvalidConfigException();
        }

        $hash = hash_hmac_file(
            'sha256',
            $path,
            http_build_query([
                'cache-version' => self::CACHE_VERSION,
                'impl-version' => ChangeLog::IMPL_VERSION,
            ]),
        );
        return $hash !== false ? $hash : throw new InvalidConfigException();
    }
}
