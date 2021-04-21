<?php

declare(strict_types=1);

namespace app\models\query;

use DateTimeInterface;
use yii\db\ActiveQuery;

class DestPresetQuery extends ActiveQuery
{
    public function gienkin(): self
    {
        $this->andWhere(['not', ['jp_gienkin_id' => null]]);
        return $this;
    }

    public function nonGienkin(): self
    {
        $this->andWhere(['jp_gienkin_id' => null]);
        return $this;
    }

    public function valid(?DateTimeInterface $now = null): self
    {
        $t = $now
            ? (int)$now->getTimestamp()
            : (int)($_SERVER['REQUEST_TIME'] ?? time()); // @codeCoverageIgnore

        $this->andWhere(['and',
            ['<=', 'valid_from', $t],
            ['>', 'valid_to', $t],
        ]);
        return $this;
    }
}
