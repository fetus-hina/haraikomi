<?php

declare(strict_types=1);

use app\assets\AppAsset;
use app\helpers\Icon;
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
<?= Html::beginTag('html', [
  'class' => 'h-100',
  'lang' => Yii::$app->language,
]) . "\n" ?>
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->render('//layouts/_favicon') . "\n" ?>
    <?= Html::csrfMetaTags() . "\n" ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head(); echo "\n" ?>
  </head>
  <body class="h-100">
<?php $this->beginBody(); echo "\n" ?>
    <?= Html::beginTag('div', [
      'class' => [
        'h-100',
        'wrap',
        'd-flex',
        'flex-column',
      ],
    ]) . "\n" ?>
      <header class="mb-3">
        <div class="container">
          <h1><a href="https://fetus.jp/">fetus</a></h1>
        </div>
      </header>
      <?= Html::tag(
        'div',
        implode('', [
          $this->render('//layouts/_navbar'),
          Html::tag('main', $content),
        ]),
        [
          'class' => [
            'container',
            'flex-grow-1',
          ],
        ],
      ) . "\n" ?>
      <footer>
        <div class="container">
          <?= implode('<br>', [
            vsprintf('Copyright &copy; 2017-%d %s %s.', [
              (int)$now->format('Y'),
              Html::a(
                Html::encode('AIZAWA Hina'),
                'https://fetus.jp/'
              ),
              implode(' ', [
                Html::a(
                  Icon::twitter(),
                  'https://twitter.com/fetus_hina'
                ),
                Html::a(
                  Icon::github(),
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
