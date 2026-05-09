<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\actions\api\PostalCodeAction;
use app\helpers\TypeHelper;
use yii\filters\VerbFilter;
use yii\web\Application;
use yii\web\Controller;

final class ApiController extends Controller
{
    /**
     * @return void
     */
    public function init()
    {
        parent::init();
        $app = TypeHelper::instanceOf(Yii::$app, Application::class);
        $app->language = 'en-US';
        $app->timeZone = 'Etc/UTC';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => VerbFilter::class,
                'actions' => [
                    'postal-code' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'postal-code' => PostalCodeAction::class,
        ];
    }
}
