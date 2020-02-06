<?php

declare(strict_types=1);

namespace app\widgets;

use Yii;
use yii\helpers\Html;

class AutoPostalCodeChoiceModal extends Modal
{
    public const ID = 'modal-postalcode-choice';

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
