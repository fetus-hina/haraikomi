<?php

declare(strict_types=1);

namespace app\widgets;

use yii\helpers\Html;

final class AutoPostalCodeChoiceModal extends Modal
{
    public static function getModalId(): string
    {
        return 'modal-postalcode-choice';
    }

    protected function getTitleText(): string
    {
        return '複数の住所が該当しました';
    }

    protected function renderBodyData(): string
    {
        return Html::tag('div', '', [
            'class' => 'list-group overflow-auto',
            'style' => [
                'max-height' => '50vh',
            ],
        ]);
    }

    protected function renderBody(): string
    {
        return Html::tag('div', $this->renderBodyData(), ['class' => 'modal-body p-0']);
    }
}
