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
    <link type="image/svg+xml" href="https://fetus.jp/images/favicon.svg" rel="icon" sizes="any">
    <link type="image/png" href="https://fetus.jp/images/apple-touch-icon-57.png" rel="apple-touch-icon" sizes="57x57">
    <link type="image/png" href="https://fetus.jp/images/apple-touch-icon-60.png" rel="apple-touch-icon" sizes="60x60">
    <link type="image/png" href="https://fetus.jp/images/apple-touch-icon-72.png" rel="apple-touch-icon" sizes="72x72">
    <link type="image/png" href="https://fetus.jp/images/apple-touch-icon-76.png" rel="apple-touch-icon" sizes="76x76">
    <link type="image/png" href="https://fetus.jp/images/apple-touch-icon-114.png" rel="apple-touch-icon" sizes="114x114">
    <link type="image/png" href="https://fetus.jp/images/apple-touch-icon-120.png" rel="apple-touch-icon" sizes="120x120">
    <link type="image/png" href="https://fetus.jp/images/apple-touch-icon-144.png" rel="apple-touch-icon" sizes="144x144">
    <link type="image/png" href="https://fetus.jp/images/apple-touch-icon-152.png" rel="apple-touch-icon" sizes="152x152">
    <link type="image/png" href="https://fetus.jp/images/apple-touch-icon-180.png" rel="apple-touch-icon" sizes="180x180">
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
        <div class="container text-end pb-3">
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
