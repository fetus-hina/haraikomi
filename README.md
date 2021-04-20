払込取扱票印刷用PDF作成機
=========================

[ゆうちょ銀行の「払込取扱票」に印刷するためのPDFを作成するサイト](https://haraikomi.fetus.jp/)のソースです。

サーバ側には（エラー発生時を除き）データは保存されませんので安心してご利用頂けます。  
保存されていないことはソースコードを精査すればわかりますが、それでも安心できない方はご自身でセットアップしてご利用いただけます。

サーバセットアップ
------------------

### 要求環境

  - OS: Linux以外では確認していません
  - PHP: PHP 7.4 以上
  - NodeJS: 最新のLTSまたは安定版
  - 適当なウェブサーバと、PHPへのつなぎ込み(Apache Module or PHP-FPM)

### セットアップ

  - `$ git clone`
  - `$ make`

ライセンス
----------

Copyright (C) 2017-2021 AIZAWA Hina  
Licensed under the MIT License.

レポジトリ中に第三者の著作物を含んでいます。  
それらのライセンスはオリジナルのライセンスに従います。

  - [OCRB代替フォント](https://www.city.aizuwakamatsu.fukushima.jp/docs/2008021400265/)
    - Licensed under the SIL Open Font License
    - (C) Aizuwakamatsu City
  - [IPAex明朝・ゴシック、IPA明朝・ゴシック](https://moji.or.jp/ipafont/)
    - Licensed under the [IPA Font License](https://moji.or.jp/ipafont/license/)
    - (C) Information-technology Promotion Agency, Japan.
  - [源真ゴシック](http://jikasei.me/font/genshin/)・[源柔ゴシック](http://jikasei.me/font/genjyuu/)
    - Licensed under the SIL Open Font License 1.1
    - (C) Adobe, M+ FONTS PROJECT, 自家製フォント工房
  - [梅明朝・ゴシック](https://ja.osdn.net/projects/ume-font/)
    - [License](resources/fonts/umefont/license.html)
  - [M+ FONTS](http://mplus-fonts.osdn.jp/)
    - License: [Japanese](resources/fonts/mplus/LICENSE_J) [English](resources/fonts/mplus/LICENSE_E)
    - (C) M+ FONTS PROJECT
  - [にゃしぃフォント改二・にゃしぃフレンズ](http://marusexijaxs.web.fc2.com/)
    - Licensed under the CC BY-ND 4.0
    - (C) マルセ / プロジェクトU-Fo
  - [Roboto Mono](https://github.com/googlefonts/roboto)
    - Licensed under the Apache License 2.0
    - (C) Google Inc.

