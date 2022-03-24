<?php

declare(strict_types=1);

use app\assets\PostalCodeAsset;
use app\helpers\Icon;
use app\models\DestPreset;
use app\models\Font;
use app\models\HaraikomiForm;
use app\models\JpGienkin;
use app\models\Prefecture;
use app\models\query\FontQuery;
use app\widgets\AutoPostalCodeChoiceModal;
use app\widgets\AutoPostalCodeHelpModal;
use app\widgets\GienkinHelpModal;
use app\widgets\LoadModal;
use app\widgets\MessageBox;
use app\widgets\SampleImageWidget;
use app\widgets\SaveHelpModal;
use app\widgets\SaveModal;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * @var View $this
 * @var HaraikomiForm $form
 */

$this->title = Yii::$app->name;

?>
<div class="site-index">
  <h1><?= Html::encode($this->title) ?></h1>
  <p class="smoothing">
    ゆうちょ銀行の「払込取扱票」に印刷するためのPDFを作成するサイトです。<br>
    「払込取扱票」はゆうちょ銀行においてあるこんな紙です。ATMとかにあります。わからなければ窓口で聞けばくれるはずです。
  </p>
  <?= SampleImageWidget::widget() ?>
  <p class="smoothing">
    自分で全部印刷するようなことは禁止されているため、ゆうちょ銀行で紙を取ってきて、そこに「手書きの代わりに」印刷する必要があります。<br>
    画像にマウスを置くと、最終的な（印刷後の）イメージが表示されます。
  </p>
  <p class="smoothing">
    入力されたデータについては一切保存していません。
    （<a href="https://github.com/fetus-hina/haraikomi">ソースコード</a>）
  </p>
  <p class="smoothing">
    作成されるPDFは、払込取扱票に合わせた特殊な用紙サイズです。(180mm×114mm)<br>
    これをうまく扱えるプリンタをうまく設定して使う必要があります。場合によっては結構難しいです。<br>
    手書きで一文字も書きたくないような人を除いて手書きしたほうが圧倒的にはやくて楽です。
  </p>
  <p class="smoothing small text-muted">
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
        <div class="text-end mb-2">
          <div class="btn-group smooting" role="group">
            <?= Html::tag(
              'button', Icon::save() . ' ' . Html::encode('Save'), [
              'type' => 'button',
              'class' => 'btn btn-sm btn-outline-secondary saver saver-save',
              'data' => [
                'label' => '払込先',
                'save' => 'to',
              ],
              'disabled' => true,
            ]) . "\n" ?>
            <?= Html::tag('button', Icon::load() . ' ' . Html::encode('Load'), [
              'type' => 'button',
              'class' => 'btn btn-sm btn-outline-secondary saver saver-load',
              'data' => [
                'label' => '払込先',
                'preset' => '#dest-preset',
                'save' => 'to',
              ],
              'disabled' => true,
            ]) . "\n" ?>
            <?= Html::tag('button', Icon::help(), [
              'type' => 'button',
              'class' => 'btn btn-sm btn-outline-secondary',
              'data' => [
                'bs-toggle' => 'modal',
                'bs-target' => '#modal-save-help',
              ],
            ]) . "\n" ?>
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
                ->andWhere(['and',
                  ['jp_gienkin_id' => null],
                  ['<=', 'valid_from', (int)$_SERVER['REQUEST_TIME']],
                  ['>', 'valid_to', (int)$_SERVER['REQUEST_TIME']],
                ])
                ->orderBy(['name' => SORT_ASC, 'id' => SORT_ASC])
                ->all(),
            )
          ) ?></script>
          <div class="btn-group smoothing" role="group">
            <?= Html::tag('button', Icon::disaster() . ' ' . Html::encode('義援金'), [
              'type' => 'button',
              'class' => 'btn btn-sm btn-outline-secondary gienkin gienkin-load',
              'data' => [
                'label' => '払込先（義援金）',
                'preset' => '#dest-gienkin',
              ],
              'disabled' => true,
            ]) . "\n" ?>
            <?= Html::tag('button', Icon::help(), [
              'type' => 'button',
              'class' => 'btn btn-sm btn-outline-secondary',
              'data' => [
                'bs-target' => '#modal-gienkin-help',
                'bs-toggle' => 'modal',
              ],
            ]) . "\n" ?>
          </div>
          <script type="application/json" id="dest-gienkin"><?= Json::encode(
            array_values(
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
            )
          ) ?></script>
        </div>

        <div class="row">
          <div class="col-12 col-lg-6">
            <label class="smoothing">記号・番号</label>
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
        <?= $_->field($form, 'use_fixed')
          ->hint(implode('<br>', [
            '簡易的な表のような通信欄を作成する必要がある場合などに利用できます。（スペースでレイアウト調整できます）',
            '「日本語フォント」オプションで次のいずれかのフォントを指定している場合のみ動作します：' . implode(
              '',
              array_map(
                fn($font) => Html::encode("「{$font->name}」"),
                Font::find()
                  ->andWhere(['not', ['fixed_id' => null]])
                  ->all(),
              )
            ),
          ]))
          ->checkbox() . "\n"
        ?>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <div class="text-end mb-2">
          <div class="btn-group smoothing" role="group">
            <?= Html::tag('button', Icon::save() . ' ' . Html::encode('Save'), [
              'type' => 'button',
              'class' => 'btn btn-sm btn-outline-secondary saver saver-save',
              'data' => [
                'save' => 'from',
                'label' => '依頼人',
              ],
              'disabled' => true,
            ]) . "\n" ?>
            <?= Html::tag('button', Icon::load() . ' ' . Html::encode('Load'), [
              'type' => 'button',
              'class' => 'btn btn-sm btn-outline-secondary saver saver-load',
              'data' => [
                'save' => 'from',
                'preset' => '#from-preset',
                'label' => '依頼人',
              ],
              'disabled' => true,
            ]) . "\n" ?>
            <?= Html::tag('button', Icon::help(), [
              'type' => 'button',
              'class' => 'btn btn-sm btn-outline-secondary',
              'data' => [
                'bs-target' => '#modal-save-help',
                'bs-toggle' => 'modal',
              ],
            ]) . "\n" ?>
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
                Html::button(
                  Html::tag('span', Html::encode('住所入力'), ['class' => 'd-inline-block smoothing']),
                  [
                    'id' => Html::getInputId($form, 'postal_code') . '--querybtn',
                    'class' => 'btn btn-outline-secondary',
                  ],
                ),
                Html::button(Icon::help(), [
                  'class' => 'btn btn-outline-secondary',
                  'data' => [
                    'bs-toggle' => 'modal',
                    'bs-target' => '#' . AutoPostalCodeHelpModal::getModalId(),
                  ],
                ]),
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
    Json::encode('#' . AutoPostalCodeChoiceModal::getModalId()),
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
              'class' => 'form-select',
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
          ])
          ->hint('払込先に要求されていないときは記入する必要はないでしょう。') . "\n"
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
        <?= $_->field($form, 'email')
          ->textInput([
            'placeholder' => 'yourname@example.com',
            'data-save-from' => 'email',
          ])
          ->hint(implode('<br>', [
            '通常は記入不要です。',
            '払込先にメールアドレスの記載を求められているときに入力してください。',
            '日本語ドメインのアドレスは使用できません。',
          ])) . "\n"
        ?>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-body">
        <?= $_->field($form, 'font_ja')
          ->dropDownList(
            $form->getJapaneseFonts(),
            ['class' => 'form-select']
          ) . "\n"
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
      implode(' ', [
        Icon::filePdf(),
        Html::tag(
          'span',
          Html::encode('作成・ダウンロード'),
          ['class' => 'd-inline-block smoothing'],
        ),
        Icon::download(),
      ]),
      ['class' => 'btn btn-primary']
    ) . "\n" ?>
  <?php ActiveForm::end(); echo "\n" ?>
</div>
<?= AutoPostalCodeChoiceModal::widget() . "\n" ?>
<?= AutoPostalCodeHelpModal::widget() . "\n" ?>
<?= LoadModal::widget() . "\n" ?>
<?= MessageBox::widget() . "\n" ?>
<?= SaveHelpModal::widget() . "\n" ?>
<?= SaveModal::widget() . "\n" ?>
<?= GienkinHelpModal::widget() . "\n" ?>
