<?php

declare(strict_types=1);

namespace app\widgets;

use Yii;
use app\assets\SampleImageAsset;
use yii\base\Widget;
use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\Html;
use yii\helpers\Json;

final class SampleImageWidget extends Widget
{
    public function run(): string
    {
        SampleImageAsset::register($this->view);
        BootstrapAsset::register($this->view);

        $this->view->registerJs(vsprintf('$(%s).sampleImage();', [
            Json::encode(sprintf('#%s', $this->id)),
        ]));

        return Html::img('@web/images/haraikomi.png', [
            'class' => 'img-fluid',
            'data' => [
                'original' => Yii::getAlias('@web/images/haraikomi.png'),
                'hover' => Yii::getAlias('@web/images/haraikomi-sample.png'),
            ],
            'id' => $this->id,
        ]);
    }
}
