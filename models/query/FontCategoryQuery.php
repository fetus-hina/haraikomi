<?php

declare(strict_types=1);

namespace app\models\query;

use Yii;
use app\models\FontCategory;
use yii\db\ActiveQuery;

class FontCategoryQuery extends ActiveQuery
{
    /** @return void */
    public function init()
    {
        parent::init();

        $table = FontCategory::tableName();
        $this->orderBy([
            "{$table}.[[rank]]" => SORT_ASC,
        ]);
    }
}
