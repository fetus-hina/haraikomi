払込取扱票印刷用PDF作成機
=========================

[ゆうちょ銀行の「払込取扱票」に印刷するためのPDFを作成するサイト](https://haraikomi.fetus.jp/)のソースです。

サーバ側には（エラー発生時を除き）データは保存されませんので安心してご利用頂けます。  
保存されていないことはソースコードを精査すればわかりますが、それでも安心できない方はご自身でセットアップしてご利用いただけます。

サーバセットアップ
------------------

### 要求環境

  - OS: Linux以外では確認していません
  - PHP: PHP 7.3.x
    - PHP 7.2.x および PHP 7.4.x では動作しません
  - NodeJS: 最新の安定版

### セットアップ

  - `$ git clone`
  - `$ make`

ライセンス
----------

Copyright (C) 2017-2019 AIZAWA Hina  
Licensed under the MIT License.

レポジトリ中に第三者の著作物を含んでいます。  
それらのライセンスはオリジナルのライセンスに従います。

  - [OCRB代替フォント](https://www.city.aizuwakamatsu.fukushima.jp/docs/2008021400265/)
    - Licensed under the SIL Open Font License
    - (C) Aizuwakamatsu City
  - [IPAex明朝](https://ipafont.ipa.go.jp/)
    - Licensed under the [IPA Font License](https://ipafont.ipa.go.jp/ipa_font_license_v1-html)
    - (C) Information-technology Promotion Agency, Japan.
  - [M+ FONTS](http://mplus-fonts.osdn.jp/)
    - License: [Japanese](resources/fonts/mplus/LICENSE_J) [English](resources/fonts/mplus/LICENSE_E)
    - (C) M+ FONTS PROJECT

