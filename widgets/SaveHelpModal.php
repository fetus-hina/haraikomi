<?php

declare(strict_types=1);

namespace app\widgets;

use yii\helpers\Html;

use function implode;

final class SaveHelpModal extends Modal
{
    protected static function getModalId(): string
    {
        return 'modal-save-help';
    }

    protected function getTitleText(): string
    {
        return '各種データの保存について';
    }

    protected function renderBodyData(): string
    {
        return implode('', [
            Html::tag('p', Html::encode(
                '横のボタンでSaveしたデータは、ブラウザに直接保存され、サーバには一切送信されません。',
            )),
            Html::tag('p', Html::encode(
                'Loadするときも含めて、インターネットに送信されることはありません。ご安心ください。',
            )),
            Html::tag(
                'div',
                Html::tag('p', Html::encode('※PDFの作成時にはデータをサーバに送信します。')),
                ['class' => 'small text-muted'],
            ),
        ]);
    }
}
