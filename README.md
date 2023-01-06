払込取扱票印刷用PDF作成機
=========================

[ゆうちょ銀行の「払込取扱票」に印刷するためのPDFを作成するサイト](https://haraikomi.fetus.jp/)のソースです。

サーバ側には（エラー発生時を除き）データは保存されませんので安心してご利用頂けます。  
保存されていないことはソースコードを精査すればわかりますが、それでも安心できない方はご自身でセットアップしてご利用いただけます。

サーバセットアップ
------------------

### 要求環境

  - OS: Linux以外では確認していません
  - PHP: PHP 8.0 以上
  - NodeJS: 最新のLTSまたは安定版
  - 適当なウェブサーバと、PHPへのつなぎ込み(Apache Module or PHP-FPM)

### セットアップ

  - `$ git clone`
  - `$ make`

### 義援金情報の更新

  - `$ curl -f 'https://www.jp-bank.japanpost.jp/aboutus/activity/fukusi/abt_act_fk_gienkin.html' | ./yii jp-bank/parse-and-import`

ライセンス
----------

Copyright (C) 2017-2022 AIZAWA Hina  
Licensed under the MIT License.

レポジトリ中に第三者の著作物を含んでいます。  
それらのライセンスはオリジナルのライセンスに従います。

  - [OCRB代替フォント](https://www.city.aizuwakamatsu.fukushima.jp/docs/2008021400265/)
    - Licensed under the SIL Open Font License
    - (C) Aizuwakamatsu City
  - [IPAex明朝・ゴシック、IPA明朝・ゴシック](https://moji.or.jp/ipafont/)
    - Licensed under the [IPA Font License](https://moji.or.jp/ipafont/license/)
    - (C) Information-technology Promotion Agency, Japan.
  - [BIZ UD明朝](https://github.com/googlefonts/morisawa-biz-ud-mincho)・[BIZ UDゴシック](https://github.com/googlefonts/morisawa-biz-ud-gothic)
    - Licensed under the SIL Open Font License 1.1
    - (C) The BIZ UDMincho Project Authors / The BIZ UDGothic Project Authors
  - [源真ゴシック](http://jikasei.me/font/genshin/)・[源柔ゴシック](http://jikasei.me/font/genjyuu/)
    - Licensed under the SIL Open Font License 1.1
    - (C) Adobe, M+ FONTS PROJECT, 自家製フォント工房
  - [梅明朝・ゴシック](https://ja.osdn.net/projects/ume-font/)
    - [License](resources/fonts/umefont/license.html)
  - [M+ FONTS](http://mplus-fonts.osdn.jp/)
    - License: [Japanese](resources/fonts/mplus/LICENSE_J) / [English](resources/fonts/mplus/LICENSE_E)
    - (C) M+ FONTS PROJECT
  - [にゃしぃフォント改二・にゃしぃフレンズ](http://marusexijaxs2.web.fc2.com/)
    - Licensed under the CC BY-ND 4.0
    - (C) マルセ / プロジェクトU-Fo
  - [JetBrains Mono NL](https://www.jetbrains.com/ja-jp/lp/mono/)
    - Licensed under the SIL Open Font License 1.1
    - (C) pyright 2020 The JetBrains Mono Project Authors
    - "0" のグリフを、「ドット0」から「スラッシュ0」に変更し、ASCII外の文字を削除しています。
