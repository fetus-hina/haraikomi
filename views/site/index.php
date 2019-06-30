<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

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
    <div class="row">
      <div class="col-12 col-lg-6">
        <label>記号・番号</label>
        <div class="row">
          <div class="col-4 col-md-5">
            <?= $_->field($form, 'account1')
              ->label(false)
              ->textInput(['placeholder' => '012345']) . "\n"
            ?>
          </div>
          <div class="col-4 col-md-2">
            <?= $_->field($form, 'account2')
              ->label(false)
              ->textInput(['placeholder' => '0']) . "\n" ?>
          </div>
          <div class="col-4 col-md-5">
            <?= $_->field($form, 'account3')
              ->label(false)
              ->textInput(['placeholder' => '98765']) . "\n" ?>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <?= $_->field($form, 'amount')
          ->textInput(['placeholder' => '3000']) . "\n" ?>
      </div>
    </div>
    <?= $_->field($form, 'account_name')
      ->textInput(['placeholder' => '㈱月極定礎ホールディングス']) . "\n" ?>

    <?= $_->field($form, 'note')->textarea(['rows' => 6]) . "\n" ?>

    <?= $_->field($form, 'postal_code')
      ->textInput(['placeholder' => '1230001']) . "\n" ?>

    <?= $_->field($form, 'pref_id')
      ->dropDownList($form->getPrefList()) . "\n" ?>

    <?= $_->field($form, 'address1')->textInput() . "\n" ?>
    <?= $_->field($form, 'address2')->textInput() . "\n" ?>
    <?= $_->field($form, 'address3')->textInput() . "\n" ?>
    <?= $_->field($form, 'name')->textInput() . "\n" ?>
    <?= $_->field($form, 'kana')->textInput() . "\n" ?>

    <div class="form-group">
      <label>電話番号</label>
      <div class="form-inline">
        <?= $_->field($form, 'phone1')->label(false)->textInput(['placeholder' => '090', 'size' => 5]) . "\n" ?>
        <?= $_->field($form, 'phone2')->label(false)->textInput(['placeholder' => '1234', 'size' => 5]) . "\n" ?>
        <?= $_->field($form, 'phone3')->label(false)->textInput(['placeholder' => '5678', 'size' => 5]) . "\n" ?>
      </div>
    </div>

<?php $this->registerCssFile('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css') ?>
    <?= Html::submitButton(
      implode('', [
        Html::tag('span', '', ['class' => 'fa fa-fw fa-download']),
        '作成・ダウンロード',
      ]),
      ['class' => 'btn btn-primary']
    ) . "\n" ?>
  <?php ActiveForm::end(); echo "\n" ?>
</div>
