<?php

use yii\helpers\Html;
use yii\web\View;

/**
 * @var Exception $exception
 * @var View $this
 * @var string $message
 * @var string $name
 */

$this->title = $name;

?>
<div class="site-error">
  <h1><?= Html::encode($this->title) ?></h1>
  <div class="alert alert-danger">
    <p class=" m-0">
      <?= nl2br(Html::encode($message)) . "\n" ?>
    </p>
  </div>
  <p>
    The above error occurred while the Web server was processing your request.
  </p>
  <p>
    Please contact us if you think this is a server error. Thank you.
  </p>
</div>
