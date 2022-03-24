<?php

declare(strict_types=1);

namespace app\widgets;

use app\helpers\Icon;
use yii\base\Widget;
use yii\bootstrap5\BootstrapAsset;
use yii\helpers\Html;

abstract class Modal extends Widget
{
    public string $id;

    /** @return void */
    public function init()
    {
        parent::init();
        $this->id = static::getModalId();
    }

    abstract protected static function getModalId(): string;
    abstract protected function getTitleText(): string;
    abstract protected function renderBodyData(): string;

    public function run()
    {
        BootstrapAsset::register($this->view);

        return Html::tag(
            'div',
            $this->renderDialog(),
            [
                'class' => 'modal fade',
                'id' => $this->id,
                'role' => 'dialog',
                'tabindex' => '-1',
            ]
        );
    }

    protected function renderDialog(): string
    {
        return Html::tag(
            'div',
            Html::tag(
                'div',
                implode('', [
                    $this->renderHeader(),
                    $this->renderBody(),
                    $this->renderFooter(),
                ]),
                [
                    'class' => 'modal-content',
                ],
            ),
            [
                'class' => 'modal-dialog',
                'role' => 'document',
            ]
        );
    }

    protected function renderHeader(): string
    {
        return Html::tag(
            'div',
            implode('', [
                $this->renderTitle(),
                $this->renderHeaderCloseButton(),
            ]),
            ['class' => 'modal-header']
        );
    }

    protected function renderTitle(): string
    {
        return Html::tag(
            'h5',
            Html::tag(
                'span',
                Html::encode($this->getTitleText()),
                ['class' => 'd-inline-block smoothing'],
            ),
            ['class' => 'modal-title'],
        );
    }

    protected function renderHeaderCloseButton(): string
    {
        return Html::button('', [
            'aria-label' => '閉じる',
            'class' => 'btn-close',
            'data-bs-dismiss' => 'modal',
        ]);
    }

    protected function renderBody(): string
    {
        return Html::tag(
            'div',
            Html::tag(
                'div',
                $this->renderBodyData(),
                ['class' => 'smoothing'],
            ),
            ['class' => 'modal-body'],
        );
    }

    protected function renderFooter(): string
    {
        return Html::tag('div', $this->renderFooterCloseButton(), ['class' => 'modal-footer']);
    }

    protected function renderFooterCloseButton(): string
    {
        return Html::button(
            Html::tag(
                'span',
                implode(' ', [
                    Icon::dismiss(),
                    Html::encode('閉じる'),
                ]),
                ['class' => 'd-inline-block smoothing'],
            ),
            [
                'class' => 'btn btn-outline-secondary',
                'data-bs-dismiss' => 'modal',
            ]
        );
    }
}
