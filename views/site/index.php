<?php
declare(strict_types=1);

use app\models\DestPreset;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;

$this->title = Yii::$app->name;
?>
<div class="site-index">
  <h1><?= Html::encode($this->title) ?></h1>
  <p>
    ゆうちょ銀行の「払込取扱票」に印刷するためのPDFを作成するサイトです。<br>
    「払込取扱票」はゆうちょ銀行においてあるこんな紙です。ATMとかにあります。わからなければ窓口で聞けばくれるはずです。<br>
    <?= Html::img('/images/haraikomi.png', [
      'class' => 'img-fluid',
      'id' => 'haraikomi-image',
      'data' => [
        'original' => '/images/haraikomi.png',
        'hover' => '/images/haraikomi-sample.png',
      ],
    ]) ?><br>
<?php $this->registerJs('$("#haraikomi-image").hover(function(){$(this).attr("src",$(this).data("hover"))},function(){$(this).attr("src",$(this).data("original"))});') ?>
    自分で全部印刷するようなことは禁止されているため、ゆうちょ銀行で紙を取ってきて、そこに「手書きの代わりに」印刷する必要があります。<br>
    画像にマウスを置くと、最終的な（印刷後の）イメージが表示されます。
  </p>
  <p>
    入力されたデータについては一切保存していません。
    （<a href="https://github.com/fetus-hina/haraikomi">ソースコード</a>）
  </p>
  <p>
    作成されるPDFは、払込取扱票に合わせた特殊な用紙サイズです。(180mm×114mm)<br>
    これをうまく扱えるプリンタをうまく設定して使う必要があります。場合によっては結構難しいです。<br>
    手書きで一文字も書きたくないような人を除いて手書きしたほうが圧倒的にはやくて楽です。
  </p>
  <p class="small text-muted">
    作成されるPDFは、選択したフォントによって字形が異なるかもしれません。<br>
    （例えば、「辻」の<ruby>辶<rp>(<rt>しんにょう<rp>)</ruby>が一点か二点か）<br>
    また、フォントによって利用できる文字が異なります。<br>
    そのあたりのケアは一切行っていませんので、出力が正しく行われていることは必ず目視で確認してください。
  </p>
  <hr>
  <h2>作成フォーム</h2>
  <?php $_ = ActiveForm::begin(); echo "\n" ?>
    <div class="card mb-3">
      <div class="card-body">
        <div class="text-right mb-2">
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary saver saver-save" data-save="to" data-label="払込先" disabled>
              <span class="far fa-save"></span> Save
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary saver saver-load" data-save="to" data-preset="#dest-preset" data-label="払込先" disabled>
              <span class="far fa-folder-open"></span> Load
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modal-save-help">
              <span class="fas fa-fw fa-info"></span>
            </button>
          </div>
          <script type="application/json" id="dest-preset"><?= Json::encode(
            array_map(
              function (DestPreset $model): array {
                return [
                  'name' => $model->name,
                  'mtime' => null,
                  'data' => [
                    'account1' => sprintf('%05d', $model->account1),
                    'account2' => (string)$model->account2,
                    'account3' => (string)$model->account3,
                    'account_name' => $model->account_name,
                  ],
                ];
              },
              DestPreset::find()->valid()->orderBy(['name' => SORT_ASC, 'id' => SORT_ASC])->all()
            )
          ) ?></script>
        </div>

        <div class="row">
          <div class="col-12 col-lg-6">
            <label>記号・番号</label>
            <div class="row">
              <div class="col-8 col-md-5">
                <?= $_->field($form, 'account1')
                  ->label(false)
                  ->textInput([
                    'data-save-to' => 'account1',
                    'placeholder' => '012345',
                  ]) . "\n"
                ?>
              </div>
              <div class="col-4 col-md-2">
                <?= $_->field($form, 'account2')
                  ->label(false)
                  ->textInput([
                    'data-save-to' => 'account2',
                    'placeholder' => '0',
                  ]) . "\n" ?>
              </div>
              <div class="col-12 col-md-5">
                <?= $_->field($form, 'account3')
                  ->label(false)
                  ->textInput([
                    'data-save-to' => 'account3',
                    'placeholder' => '98765',
                  ]) . "\n" ?>
              </div>
            </div>
          </div>
        </div>

        <?= $_->field($form, 'account_name')
          ->textInput([
            'data-save-to' => 'account_name',
            'placeholder' => '㈱月極定礎ホールディングス',
          ]) . "\n"
        ?>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <?= $_->field($form, 'amount')
          ->textInput(['placeholder' => '3000']) . "\n" ?>

        <?= $_->field($form, 'note')->textarea(['rows' => 6]) . "\n" ?>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <div class="text-right mb-2">
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary saver saver-save" data-save="from" data-label="依頼人" disabled>
              <span class="far fa-save"></span> Save
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary saver saver-load" data-save="from" data-label="依頼人" disabled>
              <span class="far fa-folder-open"></span> Load
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modal-save-help">
              <span class="fas fa-fw fa-info"></span>
            </button>
          </div>
        </div>

        <?= $_->field($form, 'postal_code')
          ->textInput([
            'placeholder' => '1000001',
            'data-save-from' => 'postal_code',
          ]) . "\n"
        ?>
        <?= $_->field($form, 'pref_id')
          ->dropDownList($form->getPrefList(), [
            'data-save-from' => 'pref_id',
            'prompt' => '---',
          ]) . "\n"
        ?>
        <?= $_->field($form, 'address1')
          ->textInput([
            'placeholder' => '千代田区',
            'data-save-from' => 'address1',
          ]) . "\n"
        ?>
        <?= $_->field($form, 'address2')
          ->textInput([
            'placeholder' => '千代田1番1号',
            'data-save-from' => 'address2',
          ]) . "\n"
        ?>
        <?= $_->field($form, 'address3')
          ->textInput([
            'placeholder' => '令和マンション1024号室',
            'data-save-from' => 'address3',
          ]) . "\n"
        ?>
        <?= $_->field($form, 'name')
          ->textInput([
            'placeholder' => '日本　太郎',
            'data-save-from' => 'name',
          ]) . "\n"
        ?>
        <?= $_->field($form, 'kana')
          ->textInput([
            'placeholder' => 'ニッポン　タロウ',
            'data-save-from' => 'kana',
          ]) . "\n"
        ?>
        <div class="form-group mb-0">
          <label>電話番号</label>
          <div class="row">
            <div class="col-4 col-md-3 col-lg-2">
              <?= $_->field($form, 'phone1')->label(false)->textInput([
                'placeholder' => '090',
                'size' => 5,
                'data-save-from' => 'phone1',
              ]) . "\n" ?>
            </div>
            <div class="col-4 col-md-3 col-lg-2">
              <?= $_->field($form, 'phone2')->label(false)->textInput([
                'placeholder' => '1234',
                'size' => 5,
                'data-save-from' => 'phone2',
              ]) . "\n" ?>
            </div>
            <div class="col-4 col-md-3 col-lg-2">
              <?= $_->field($form, 'phone3')->label(false)->textInput([
                'placeholder' => '5678',
                'size' => 5,
                'data-save-from' => 'phone3',
              ]) . "\n" ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= $_->field($form, 'font_ja')
      ->dropDownList($form->getJapaneseFonts()) . "\n"
    ?>
    <?= Html::submitButton(
      implode('', [
        Html::tag('span', '', ['class' => 'fas fa-fw fa-download']),
        '作成・ダウンロード',
      ]),
      ['class' => 'btn btn-primary']
    ) . "\n" ?>
  <?php ActiveForm::end(); echo "\n" ?>
  <hr>
  <h2>更新履歴</h2>
  <ul>
    <li>
      2019-07-17
      <ul>
        <li>
          数字以外の部分（主に日本語記載部分）のフォントとして次のフォントが利用できるようになりました。
          おまけみたいなものなので通常はIPAex明朝を利用されるのがいいと思います。
          <ul>
            <li>
              <a href="https://ipafont.ipa.go.jp/">IPAexゴシック</a>
            </li>
            <li>
              <a href="https://ja.osdn.net/projects/ume-font/">梅明朝・梅ゴシック</a>
            </li>
            <li>
              <a href="http://mplus-fonts.osdn.jp/">M+1p Regular</a>
            </li>
            <li>
              <a href="http://www001.upp.so-net.ne.jp/mikachan/">みかちゃん-P</a>
            </li>
            <li>
              <a href="http://marusexijaxs.web.fc2.com/tegakifont.html">にゃしぃフォント改二・にゃしぃフレンズ</a>
            </li>
          </ul>
        </li>
      </ul>
    </li>
    <li>
      2019-07-15
      <ul>
        <li>
          依頼人各データの必須指定を無くしました。
          これにより、「自分の口座情報を印刷した配布用の払込票」が作成できます。
          ゆうちょ銀行の想定した使い方ではおそらくないと思いますので、
          実際のご利用の前にゆうちょ銀行へ相談されたほうがいいと思います。
          何か問題が発生しても一切責任は負いません。
        </li>
      </ul>
    </li>
    <li>
      2019-07-05
      <ul>
        <li>
          日本語印刷に利用している<a href="https://ipafont.ipa.go.jp/">IPAex明朝フォント</a>を
          Ver.004.01 に更新しました。「令和」の組文字（U+32FF, ㋿）が出力できるようになっていたりします。
          （が、㍻に比べて細いような…）
        </li>
      </ul>
    </li>
    <li>
      2019-07-02
      <ul>
        <li>入力欄の大きさやヒントの文字色などを調整しました。</li>
      </ul>
    </li>
    <li>
      2019-07-01
      <ul>
        <li>入力欄を整理しました。</li>
        <li>
          払込先・依頼人をブラウザに記憶できるようになりました。
          プリセットとして払込先には現時点で受付中の災害義援金の払込先を用意しておきました。
          どんな感じに動くかの確認に使えると思います。
        </li>
      </ul>
    </li>
    <li>
      これ以前は省略
    </li>
  </ul>
</div>
<div class="modal fade" id="modal-save-help" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">各種データの保存について</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
          <span class="fas fa-times"></span>
        </button>
      </div>
      <div class="modal-body">
        <p>横のボタンで Save したデータは、ブラウザに直接保存され、サーバには一切送信されません。</p>
        <p>Load するときも含めて、インターネットに送信されることはありません。ご安心ください。</p>
        <div class="small text-muted">
          <p>※PDFの作成時にはデータをサーバに送信します。</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
          <span class="fas fa-times"></span> 閉じる
        </button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-save" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">保存</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
          <span class="fas fa-times"></span>
        </button>
      </div>
      <div class="modal-body">
        <p>保存名を入力してください（読込はこの名前で行います）</p>
        <div class="form-group">
          <input type="text" class="form-control" name="name">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary btn-save">
          <span class="fas fa-save"></span> 保存
        </button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-load" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">読込</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
          <span class="fas fa-times"></span>
        </button>
      </div>
      <div class="modal-body">
        <p>読込対象を選択してください</p>
        <div class="form-group">
          <select name="target" class="form-control">
          </select>
        </div>
        <p class="preset-notice d-none text-muted small">
          プリセットの内容は参考情報です。<br>
          払込先の記号番号などが正しいことは必ずご自身で確認してください。
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary btn-load">
          <span class="fas fa-folder-open"></span> 読込
        </button>
      </div>
    </div>
  </div>
</div>
