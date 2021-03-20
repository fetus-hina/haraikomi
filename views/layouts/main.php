<?php

declare(strict_types=1);

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var string $content
 */

AppAsset::register($this);

$now = (new DateTimeImmutable('now', new DateTimeZone('Asia/Tokyo')))
  ->setTimestamp((int)($_SERVER['REQUEST_TIME'] ?? time()));
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() . "\n" ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head(); echo "\n" ?>
  </head>
  <body>
  <?php $this->beginBody(); echo "\n" ?>
    <div class="wrap">
      <header class="mb-3">
        <div class="container">
          <h1><a href="https://fetus.jp/">fetus</a></h1>
        </div>
      </header>
      <div class="container">
        <?= $content . "\n" ?>
      </div>
      <footer>
        <hr>
        <div class="container text-right pb-3">
          <?= implode('<br>', [
            vsprintf('Copyright &copy; 2017-%d %s %s.', [
              (int)$now->format('Y'),
              Html::a(
                Html::encode('AIZAWA Hina'),
                'https://fetus.jp/'
              ),
              implode(' ', [
                Html::a(
                  Html::tag('span', '', ['class' => 'fab fa-twitter']),
                  'https://twitter.com/fetus_hina'
                ),
                Html::a(
                  Html::tag('span', '', ['class' => 'fab fa-github']),
                  'https://github.com/fetus-hina'
                ),
              ]),
            ]),
            vsprintf('Powered by %s.', [
              preg_replace(
                '/,(?=[^,]+$)/', // 最後のカンマ
                ' and ',
                implode(', ', [
                  Html::a(
                    Html::encode('Yii Framework'),
                    'https://www.yiiframework.com/',
                  ),
                  Html::a(
                    Html::encode('TCPDF'),
                    'https://tcpdf.org/',
                  ),
                ])
              ),
            ]),
          ]) . "\n" ?>
        </div>
      </footer>
    </div>
  <?php $this->endBody(); echo "\n" ?>
  </body>
</html>
<?php $this->endPage() ?>
