<?php

declare(strict_types=1);

namespace app\models\query;

use Yii;
use app\models\Font;
use app\models\FontCategory;
use yii\db\ActiveQuery;

class FontQuery extends ActiveQuery
{
    /** @return void */
    public function init()
    {
        parent::init();

        $fontTable = Font::tableName();
        $catTable = FontCategory::tableName();
        $this
            ->innerJoinWith('category')
            ->orderBy([
                "{$catTable}.[[rank]]" => SORT_ASC,
                "{$fontTable}.[[rank]]" => SORT_ASC,
            ]);
    }
}
