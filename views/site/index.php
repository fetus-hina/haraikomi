<?php

declare(strict_types=1);

use app\assets\PostalCodeAsset;
use app\models\DestPreset;
use app\models\JpGienkin;
use app\models\Prefecture;
use app\widgets\AutoPostalCodeChoiceModal;
use app\widgets\AutoPostalCodeHelpModal;
use app\widgets\GienkinHelpModal;
use app\widgets\LoadModal;
use app\widgets\MessageBox;
use app\widgets\SaveHelpModal;
use app\widgets\SaveModal;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
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
              DestPreset::find()
                ->valid()
                ->nonGienkin()
                ->orderBy([
                    'name' => SORT_ASC,
                    'id' => SORT_ASC,
                ])
                ->all()
            )
          ) ?></script>
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary gienkin gienkin-load" data-preset="#dest-gienkin" data-label="払込先（義援金）" disabled>
              <span class="fas fa-cloud-showers-heavy"></span> 義援金
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modal-gienkin-help">
              <span class="fas fa-fw fa-info"></span>
            </button>
          </div>
          <script type="application/json" id="dest-gienkin"><?= Json::encode(
            array_filter(
              array_map(
                function (JpGienkin $gienkin): array {
                  return [
                    'name' => $gienkin->name,
                    'presets' => array_map(
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
                      $gienkin->destPresets,
                    ),
                  ];
                },
                JpGienkin::find()
                  ->with('destPresets')
                  ->orderBy([
                    'ref_time' => SORT_DESC,
                    'name' => SORT_ASC,
                  ])
                  ->all(),
              ),
              function (array $data): bool {
                return !empty($data['presets']);
              },
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
            <button type="button" class="btn btn-sm btn-outline-secondary saver saver-load" data-save="from" data-preset="#from-preset" data-label="依頼人" disabled>
              <span class="far fa-folder-open"></span> Load
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modal-save-help">
              <span class="fas fa-fw fa-info"></span>
            </button>
          </div>
          <script type="application/json" id="from-preset"><?= Json::encode([
            [
              'name' => 'ダミーデータ：皇居',
              'mtime' => null,
              'data' => [
                'postal_code' => '1000001',
                'pref_id' => 13,
                'address1' => '千代田区',
                'address2' => '千代田1番1号',
                'address3' => '',
                'name' => '令和　太郎',
                'kana' => 'レイワ　タロウ',
                'phone1' => '090',
                'phone2' => '1234',
                'phone3' => '5678',
              ],
            ],
            [
              'name' => 'ダミーデータ：東京都庁',
              'mtime' => null,
              'data' => [
                'postal_code' => '1638001',
                'pref_id' => 13,
                'address1' => '新宿区',
                'address2' => '西新宿2-8-1',
                'address3' => '東京都庁45階',
                'name' => '令和　太郎',
                'kana' => 'レイワ　タロウ',
                'phone1' => '080',
                'phone2' => '1234',
                'phone3' => '5678',
              ],
            ],
            [
              'name' => 'ダミーデータ：大阪府庁',
              'mtime' => null,
              'data' => [
                'postal_code' => '5408570',
                'pref_id' => 27,
                'address1' => '大阪市中央区',
                'address2' => '大手前2-1-22',
                'address3' => '',
                'name' => '令和　太郎',
                'kana' => 'レイワ　タロウ',
                'phone1' => '070',
                'phone2' => '1234',
                'phone3' => '5678',
              ],
            ],
          ]) ?></script>
        </div>

        <?= $_->field($form, 'postal_code', [
            'inputTemplate' => Html::tag(
              'div',
              implode('', [
                '{input}',
                Html::tag(
                  'div',
                  implode('', [
                    Html::button(Html::encode('住所入力'), [
                      'id' => Html::getInputId($form, 'postal_code') . '--querybtn',
                      'class' => 'btn btn-outline-secondary',
                    ]),
                    Html::button(Html::tag('span', '', ['class' => 'fas fa-info fa-fw']), [
                      'class' => 'btn btn-outline-secondary',
                      'data' => [
                        'toggle' => 'modal',
                        'target' => '#' . AutoPostalCodeHelpModal::ID,
                      ],
                    ]),
                  ]),
                  ['class' => 'input-group-append']
                ),
              ]),
              ['class' => 'input-group']
            ),
          ])
          ->textInput([
            'placeholder' => '1000001',
            'data-save-from' => 'postal_code',
          ]) . "\n"
        ?>
<?php
PostalCodeAsset::register($this);
$this->registerJs(vsprintf('$(%s).postalcode(%s);', [
  Json::encode('#' . Html::getInputId($form, 'postal_code') . '--querybtn'),
  implode(',', [
    Json::encode('#' . Html::getInputId($form, 'postal_code')),
    Json::encode('#' . AutoPostalCodeChoiceModal::ID),
    Json::encode([
      '#' . Html::getInputId($form, 'pref_id') => 'prefcode',
      '#' . Html::getInputId($form, 'address1') => 'address2',
      '#' . Html::getInputId($form, 'address2') => 'address3',
      '#' . Html::getInputId($form, 'address3') => null,
    ]),
  ]),
]));
?>
        <?= $_->field($form, 'pref_id')
          ->dropDownList(
            ArrayHelper::map(
              Prefecture::find()->all(),
              'id',
              'name'
            ),
            [
              'data-save-from' => 'pref_id',
              'prompt' => '---',
            ]
          ) . "\n"
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
    <div class="card mb-3">
      <div class="card-body">
        <?= $_->field($form, 'font_ja')
          ->dropDownList($form->getJapaneseFonts()) . "\n"
        ?>
        <?= $_->field($form, 'draw_form')
          ->hint(implode('<br>', [
              '印刷後のイメージを確認するために、罫線等を描画します。',
              'このオプションを使用して出力したデータを実際に印刷して利用することはできません。',
          ]))
          ->checkbox() . "\n"
        ?>
      </div>
    </div>
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
      2020-07-10
      <ul>
        <li>
          義援金口座情報を更新しました。
          うまくいけば、今後は自動的に最新の情報に更新されます。
        </li>
      </ul>
    </li>
    <li>
      2019-10-18
      <ul>
        <li>
          台風19号の義援金口座情報を追加しました。
        </li>
      </ul>
    </li>
    <li>
      2019-09-19
      <ul>
        <li>
          台風15号の義援金口座情報を追加しました。
        </li>
        <li>
          <a href="http://marusexijaxs.web.fc2.com/tegakifont.html">にゃしぃフォント改二・にゃしぃフレンズ</a>をVersion 2.075に更新しました。「㋿」に対応したようです。
        </li>
      </ul>
    </li>
    <li>
      2019-09-15
      <ul>
        <li>
          京都アニメーション放火事件の義援金口座情報を追加しました。
        </li>
        <li>
          令和元年8月の前線に伴う大雨による災害に対する義援金口座情報を追加しました。
        </li>
      </ul>
    </li>
    <li>
      2019-07-21
      <ul>
        <li>
          フォントを追加しました。
          源ノ角ゴシック（Noto Sans CJK / Source Han Sans）ベースのフォントです。
          <ul>
            <li>
              <a href="http://jikasei.me/font/genshin/">源真ゴシック</a>
            </li>
            <li>
              <a href="http://jikasei.me/font/genjyuu/">源柔ゴシック</a>
            </li>
          </ul>
        </li>
      </ul>
    </li>
    <li>
      2019-07-19
      <ul>
        <li>
          「罫線等を描画する」オプションを実装しました。
          印刷しない状態でなんとなくのできあがりイメージがつかめるPDFが出力されますが、
          このオプションを有効にしたPDFを実際に印刷して利用することはできません。
        </li>
        <li>
          右側の「加入者名」が小さくなる場合の処理を変更しました。
          とりあえず適当な場所で折り返されて出力されます。
          文脈とかは読まないので、微妙な折り返しが行われます。
        </li>
      </ul>
    </li>
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
<?= AutoPostalCodeChoiceModal::widget() . "\n" ?>
<?= AutoPostalCodeHelpModal::widget() . "\n" ?>
<?= LoadModal::widget() . "\n" ?>
<?= MessageBox::widget() . "\n" ?>
<?= SaveHelpModal::widget() . "\n" ?>
<?= SaveModal::widget() . "\n" ?>
<?= GienkinHelpModal::widget() . "\n" ?>
