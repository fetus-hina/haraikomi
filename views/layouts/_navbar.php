<?php

declare(strict_types=1);

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

NavBar::begin([
  'brandLabel' => Yii::$app->name,
  'options' => [
    'class' => [
      'widget' => 'navbar',
      'toggle' => 'navbar-expand-md',
      'bg-dark',
      'mb-4',
      'navbar-dark',
      'px-3',
      'rounded',
      'shadow',
      'smoothing',
    ],
  ],
]);
echo Nav::widget([
  'items' => [
    [
      'label' => 'トップ',
      'url' => ['site/index'],
      'options' => [
        'class' => 'smoothing',
      ],
    ],
    [
      'label' => '変更履歴',
      'url' => ['site/history'],
      'options' => [
        'class' => 'smoothing'
      ],
    ],
  ],
  'options' => [
    'class' => [
      'navbar-nav',
    ],
  ],
]);
echo Nav::widget([
  'items' => [
    [
      'label' => 'fetus.jp',
      'url' => 'https://fetus.jp/',
      'options' => [
        'class' => 'smoothing',
      ],
    ],
  ],
  'options' => [
    'class' => [
      'ms-auto',
      'navbar-nav',
    ],
  ],
]);
NavBar::end();
