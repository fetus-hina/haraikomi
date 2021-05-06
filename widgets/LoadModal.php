<?php

declare(strict_types=1);

namespace app\widgets;

use Yii;
use yii\helpers\Html;

final class LoadModal extends Modal
{
    protected static function getModalId(): string
    {
        return 'modal-load';
    }

    protected function getTitleText(): string
    {
        return '読込';
    }

    protected function renderBodyData(): string
    {
        return implode('', [
            Html::tag('p', Html::encode('読込対象を選択してください')),
            Html::tag(
                'div',
                Html::tag('select', '', [
                    'name' => 'target',
                    'class' => 'form-select',
                ]),
                ['class' => 'form-group']
            ),
            Html::tag(
                'p',
                implode('<br>', [
                    Html::encode('プリセットの内容は参考情報です。'),
                    Html::encode('払込先の記号番号などが正しいことは必ずご自身で確認してください。'),
                ]),
                ['class' => 'preset-notice d-none text-muted small']
            ),
        ]);
    }

    protected function renderFooterCloseButton(): string
    {
        return Html::button(
            implode(' ', [
                Html::tag('span', '', ['class' => 'fas fa-folder-open']),
                Html::encode('読込'),
            ]),
            [
                'class' => 'btn btn-outline-primary btn-load',
            ]
        );
    }
}
