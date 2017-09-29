<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::$app->name;
?>
<div class="site-index">
  <h1><?= Html::encode($this->title) ?></h1>
  <hr>
  <h2>作成フォーム</h2>
  <?php $_ = ActiveForm::begin(); echo "\n" ?>
    <div class="row">
      <div class="col-12 col-lg-6">
        <label>記号・番号</label>
        <div class="row">
          <div class="col-5">
            <?= $_->field($form, 'account1')
              ->label(false)
              ->textInput(['placeholder' => '012345']) . "\n"
            ?>
          </div>
          <div class="col-2">
            <?= $_->field($form, 'account2')
              ->label(false)
              ->textInput(['placeholder' => '0']) . "\n" ?>
          </div>
          <div class="col-5">
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

    <?= $_->field($form, 'postal_code')
      ->textInput(['placeholder' => '1230001']) . "\n" ?>

    <?= $_->field($form, 'pref_id')
      ->dropDownList($form->getPrefList()) . "\n" ?>

    <?= $_->field($form, 'address1')->textInput() . "\n" ?>
    <?= $_->field($form, 'address2')->textInput() . "\n" ?>
    <?= $_->field($form, 'address3')->textInput() . "\n" ?>
    <?= $_->field($form, 'name')->textInput() . "\n" ?>

    <div class="form-group">
      <label>電話番号</label>
      <div class="form-inline">
        <?= $_->field($form, 'phone1')->label(false)->textInput(['placeholder' => '090', 'size' => 5]) ?> -
        <?= $_->field($form, 'phone2')->label(false)->textInput(['placeholder' => '1234', 'size' => 5]) ?> -
        <?= $_->field($form, 'phone3')->label(false)->textInput(['placeholder' => '5678', 'size' => 5]) . "\n" ?>
      </div>
    </div>

    <?= Html::submitButton('作成', ['class' => 'btn btn-primary']) . "\n" ?>
  <?php ActiveForm::end(); echo "\n" ?>
</div>
