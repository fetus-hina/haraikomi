<?php

declare(strict_types=1);

namespace app\widgets;

use Yii;
use yii\helpers\Html;

class GienkinHelpModal extends Modal
{
    protected static function getModalId(): string
    {
        return 'modal-gienkin-help';
    }

    protected function getTitleText(): string
    {
        return '義援金送金用振替口座について';
    }

    protected function renderBodyData(): string
    {
        return implode('', [
            Html::tag('p', Html::encode(
                'ゆうちょ銀行の義援金送金用振替口座をプリセットとして保存してあります。',
            )),
            Html::tag('p', Html::encode(
                'これらの口座へ窓口から振り込みを行う際は手数料が無料になります。（ATMからの場合は有料です）',
            )),
            Html::tag('p', Html::encode(
                'ここで設定している内容はあくまで参考情報です。' .
                '必ずご自身で実際の対象であること及び救援団体があなたの意向に沿うものであることを確認してください。'
            )),
            Html::tag('p', Html::a(
                Html::encode('ゆうちょ銀行の案内ページ'),
                'https://www.jp-bank.japanpost.jp/aboutus/activity/fukusi/abt_act_fk_gienkin.html',
                ['target' => '_blank', 'rel' => 'external']
            )),
            Html::tag('p', Html::encode(
                'この機能を利用したことによって発生したすべての事象について、一切責任を負いません。',
            )),
        ]);
    }
}
