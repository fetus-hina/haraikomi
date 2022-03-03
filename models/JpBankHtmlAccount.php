<?php

declare(strict_types=1);

namespace app\models;

use DateTimeInterface;
use yii\base\Model;

/**
 * @property-read array $json
 */
final class JpBankHtmlAccount extends Model
{
    public string $disaster;
    public string $accountName;

    /** @var array{0: int, 1: int, 2: int} */
    public array $account = [0, 0, 0];

    public DateTimeInterface $start;
    public DateTimeInterface $end;

    public function getJson(): array
    {
        $props = $this->attributes;
        return array_map(
            fn ($v) => $v instanceof DateTimeInterface ? $v->format('Y-m-d') : $v,
            $props,
        );
    }
}
