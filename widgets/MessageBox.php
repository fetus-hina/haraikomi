<?php

declare(strict_types=1);

namespace app\widgets;

use app\assets\MessageBoxAsset;
use app\helpers\Icon;
use yii\helpers\Html;

final class MessageBox extends Modal
{
    public static function getModalId(): string
    {
        return 'message-box';
    }

    /** @return void */
    public function init()
    {
        parent::init();
        MessageBoxAsset::register($this->view);
    }

    protected function getTitleText(): string
    {
        return 'To be filled';
    }

    protected function renderBodyData(): string
    {
        return Html::tag(
            'div',
            implode('', [
                $this->renderIcons(),
                $this->renderContentPlaceholder(),
            ]),
            ['class' => 'd-flex']
        );
    }

    protected function renderIcons(): string
    {
        return Html::tag(
            'div',
            implode('', [
                $this->renderIconInformation(),
                $this->renderIconWarning(),
                $this->renderIconError(),
            ]),
            [
                'class' => 'mr-4',
            ]
        );
    }

    protected function renderIconInformation(): string
    {
        return $this->renderIcon(
            Icon::dialogInfo(),
            'text-info modal-icon-info'
        );
    }

    protected function renderIconWarning(): string
    {
        return $this->renderIcon(
            Icon::dialogWarning(),
            'text-warning modal-icon-warning'
        );
    }

    protected function renderIconError(): string
    {
        return $this->renderIcon(
            Icon::dialogError(),
            'text-danger modal-icon-error'
        );
    }

    private function renderIcon(string $iconHtml, string $colorClass): string
    {
        return Html::tag('span', $iconHtml, [
            'class' => $colorClass,
            'style' => [
                'font-size' => '4rem',
            ],
        ]);
    }

    protected function renderContentPlaceholder(): string
    {
        return Html::tag('div', 'To be filled', [
            'class' => 'flex-grow-1',
            'id' => $this->id . '-content',
        ]);
    }
}
