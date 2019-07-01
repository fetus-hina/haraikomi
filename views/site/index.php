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
    <img src="/images/haraikomi.png" class="img-fluid"><br>
    自分で全部印刷するようなことは禁止されているため、ゆうちょ銀行で紙を取ってきて、そこに「手書きの代わりに」印刷する必要があります。
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
              <div class="col-4 col-md-5">
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
              <div class="col-4 col-md-5">
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
            'placeholder' => '1230001',
            'data-save-from' => 'postal_code',
          ]) . "\n"
        ?>
        <?= $_->field($form, 'pref_id')
          ->dropDownList($form->getPrefList(), [
            'data-save-from' => 'pref_id',
          ]) . "\n"
        ?>
        <?= $_->field($form, 'address1')->textInput(['data-save-from' => 'address1']) . "\n" ?>
        <?= $_->field($form, 'address2')->textInput(['data-save-from' => 'address2']) . "\n" ?>
        <?= $_->field($form, 'address3')->textInput(['data-save-from' => 'address3']) . "\n" ?>
        <?= $_->field($form, 'name')->textInput(['data-save-from' => 'name']) . "\n" ?>
        <?= $_->field($form, 'kana')->textInput(['data-save-from' => 'kana']) . "\n" ?>
        <div class="form-group">
          <label>電話番号</label>
          <div class="form-inline">
            <?= $_->field($form, 'phone1')->label(false)->textInput([
              'placeholder' => '090',
              'size' => 5,
              'data-save-from' => 'phone1',
            ]) . "\n" ?>
            <?= $_->field($form, 'phone2')->label(false)->textInput([
              'placeholder' => '1234',
              'size' => 5,
              'data-save-from' => 'phone2',
            ]) . "\n" ?>
            <?= $_->field($form, 'phone3')->label(false)->textInput([
              'placeholder' => '5678',
              'size' => 5,
              'data-save-from' => 'phone3',
            ]) . "\n" ?>
          </div>
        </div>
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
