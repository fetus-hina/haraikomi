<?php

declare(strict_types=1);

use yii\web\View;

/**
 * @var View $this
 */

$this->title = '更新履歴 - ' . Yii::$app->name;

?>
<div class="site-history">
  <h2>更新履歴</h2>
  <ul>
    <li class="smoothing">
      2022-01-13
      <ul>
        <li class="smoothing">
          ページの構成を変更しました。機能に変更はありません。
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2021-10-07
      <ul>
        <li class="smoothing">
          フォントを更新しました。
          <ul>
            <li class="smoothing">にゃしぃフォント改二: Version 2.084</li>
            <li class="smoothing">にゃしぃフレンズ: Version 2.084</li>
          </ul>
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2021-06-19
      <ul>
        <li class="smoothing">
          フォントを更新しました。
          <ul>
            <li class="smoothing">にゃしぃフォント改二: Version 2.08</li>
            <li class="smoothing">にゃしぃフレンズ: Version 2.08</li>
          </ul>
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2021-04-20
      <ul>
        <li class="smoothing">みかちゃん-Pを削除しました。</li>
        <li class="smoothing">フォント「M+ 1m」を追加しました。</li>
        <li class="smoothing">「M+ 1p」を指定時に「通信欄への等幅フォントの利用」が利用できるようになりました（M+ 1mが利用されます）。</li>
      </ul>
    </li>
    <li class="smoothing">
      2021-02-06
      <ul>
        <li class="smoothing">
          フォントを更新しました。
          <ul>
            <li class="smoothing">にゃしぃフォント改二: Version 2.0710</li>
            <li class="smoothing">にゃしぃフレンズ: Version 2.0710</li>
          </ul>
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2020-07-21
      <ul>
        <li class="smoothing">
          依頼人メールアドレス欄を追加しました。
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2020-07-13
      <ul>
        <li class="smoothing">
          利用可能フォントを追加しました。
          <ul>
            <li class="smoothing">IPA明朝</li>
            <li class="smoothing">IPAゴシック</li>
          </ul>
        </li>
        <li class="smoothing">
          備考欄に等幅フォントを使用するオプションを追加しました。<br>
          通販の申し込みの際に、簡易的な表のようなもの（複数行での位置調整が必要なもの）を印刷できるようになります。<br>
          このオプションは、IPAex明朝またはIPAexゴシックを使用フォントとして指定している場合に動作します。<br>
          それ以外のフォントでは無視されます。
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2020-07-10
      <ul>
        <li class="smoothing">
          義援金口座情報を更新しました。
          うまくいけば、今後は自動的に最新の情報に更新されます。
        </li>
        <li class="smoothing">
          フォントを更新しました。
          <ul>
            <li class="smoothing">にゃしぃフォント改二: Version 2.077</li>
            <li class="smoothing">にゃしぃフレンズ: Version 2.077</li>
          </ul>
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2019-10-18
      <ul>
        <li class="smoothing">
          台風19号の義援金口座情報を追加しました。
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2019-09-19
      <ul>
        <li class="smoothing">
          台風15号の義援金口座情報を追加しました。
        </li>
        <li class="smoothing">
          <a href="http://marusexijaxs.web.fc2.com/tegakifont.html">にゃしぃフォント改二・にゃしぃフレンズ</a>をVersion 2.075に更新しました。「㋿」に対応したようです。
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2019-09-15
      <ul>
        <li class="smoothing">
          京都アニメーション放火事件の義援金口座情報を追加しました。
        </li>
        <li class="smoothing">
          令和元年8月の前線に伴う大雨による災害に対する義援金口座情報を追加しました。
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2019-07-21
      <ul>
        <li class="smoothing">
          フォントを追加しました。
          源ノ角ゴシック（Noto Sans CJK / Source Han Sans）ベースのフォントです。
          <ul>
            <li class="smoothing">
              <a href="http://jikasei.me/font/genshin/">源真ゴシック</a>
            </li>
            <li class="smoothing">
              <a href="http://jikasei.me/font/genjyuu/">源柔ゴシック</a>
            </li>
          </ul>
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2019-07-19
      <ul>
        <li class="smoothing">
          「罫線等を描画する」オプションを実装しました。
          印刷しない状態でなんとなくのできあがりイメージがつかめるPDFが出力されますが、
          このオプションを有効にしたPDFを実際に印刷して利用することはできません。
        </li>
        <li class="smoothing">
          右側の「加入者名」が小さくなる場合の処理を変更しました。
          とりあえず適当な場所で折り返されて出力されます。
          文脈とかは読まないので、微妙な折り返しが行われます。
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2019-07-17
      <ul>
        <li class="smoothing">
          数字以外の部分（主に日本語記載部分）のフォントとして次のフォントが利用できるようになりました。
          おまけみたいなものなので通常はIPAex明朝を利用されるのがいいと思います。
          <ul>
            <li class="smoothing">
              <a href="https://moji.or.jp/ipafont/">IPAexゴシック</a>
            </li>
            <li class="smoothing">
              <a href="https://ja.osdn.net/projects/ume-font/">梅明朝・梅ゴシック</a>
            </li>
            <li class="smoothing">
              <a href="http://mplus-fonts.osdn.jp/">M+1p Regular</a>
            </li>
            <li class="smoothing">
              みかちゃん-P
            </li>
            <li class="smoothing">
              <a href="http://marusexijaxs.web.fc2.com/tegakifont.html">にゃしぃフォント改二・にゃしぃフレンズ</a>
            </li>
          </ul>
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2019-07-15
      <ul>
        <li class="smoothing">
          依頼人各データの必須指定を無くしました。
          これにより、「自分の口座情報を印刷した配布用の払込票」が作成できます。
          ゆうちょ銀行の想定した使い方ではおそらくないと思いますので、
          実際のご利用の前にゆうちょ銀行へ相談されたほうがいいと思います。
          何か問題が発生しても一切責任は負いません。
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2019-07-05
      <ul>
        <li class="smoothing">
          日本語印刷に利用している<a href="https://moji.or.jp/ipafont/">IPAex明朝フォント</a>を
          Ver.004.01 に更新しました。「令和」の組文字（U+32FF, ㋿）が出力できるようになっていたりします。
          （が、㍻に比べて細いような…）
        </li>
      </ul>
    </li>
    <li class="smoothing">
      2019-07-02
      <ul>
        <li class="smoothing">入力欄の大きさやヒントの文字色などを調整しました。</li>
      </ul>
    </li>
    <li class="smoothing">
      2019-07-01
      <ul>
        <li class="smoothing">入力欄を整理しました。</li>
        <li class="smoothing">
          払込先・依頼人をブラウザに記憶できるようになりました。
          プリセットとして払込先には現時点で受付中の災害義援金の払込先を用意しておきました。
          どんな感じに動くかの確認に使えると思います。
        </li>
      </ul>
    </li>
    <li class="smoothing">
      これ以前は省略
    </li>
  </ul>
</div>
