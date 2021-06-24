<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\actions\api\PostalCodeAction;
use yii\filters\VerbFilter;
use yii\web\Controller;

final class ApiController extends Controller
{
    /** @return void */
    public function init()
    {
        parent::init();
        Yii::$app->language = 'en-US';
        Yii::$app->timeZone = 'Etc/UTC';
    }

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

    public function actions()
    {
        return [
            'postal-code' => PostalCodeAction::class,
        ];
    }
}
