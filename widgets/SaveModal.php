<?php

declare(strict_types=1);

namespace app\widgets;

use app\helpers\Icon;
use yii\helpers\Html;

use function implode;

final class SaveModal extends Modal
{
    protected static function getModalId(): string
    {
        return 'modal-save';
    }

    protected function getTitleText(): string
    {
        return '保存';
    }

    protected function renderBodyData(): string
    {
        return implode('', [
            Html::tag('p', Html::encode('保存名を入力してください（読込はこの名前で行います）')),
            Html::tag(
                'div',
                Html::textInput('name', '', ['class' => 'form-control']),
                ['class' => 'form-group'],
            ),
        ]);
    }

    protected function renderFooterCloseButton(): string
    {
        return Html::button(
            implode(' ', [
                Icon::save(),
                Html::encode('保存'),
            ]),
            [
                'class' => 'btn btn-outline-primary btn-save',
            ],
        );
    }
}
