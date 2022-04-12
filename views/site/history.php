<?php

declare(strict_types=1);

use app\assets\HistoryAsset;
use app\widgets\ChangeLogEntry;
use yii\helpers\Html;
use yii\web\View;

/**
 * @phpstan-var array{date: non-empty-string, content: non-empty-string}[] $entries
 * @var View $this
 */

$this->title = '更新履歴 - ' . Yii::$app->name;

HistoryAsset::register($this);

?>
<div class="site-history">
  <h2>更新履歴</h2>
  <ul>
<?php foreach ($entries as $entry) { ?>
    <li><?= ChangeLogEntry::widget(['entry' => $entry]) ?></li>
<?php } ?>
    <li>これ以前は省略</li>
  </ul>
</div>
