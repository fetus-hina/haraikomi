<?php

declare(strict_types=1);

namespace app\controllers;

use DateTimeImmutable;
use DateTimeZone;
use Yii;
use app\models\HaraikomiForm;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $form = Yii::createObject(HaraikomiForm::class);
        if ($form->load($_POST) && $form->validate()) {
            $resp = Yii::$app->response;
            $resp->format = 'raw';
            $resp->setDownloadHeaders(
                sprintf(
                    'haraikomi-%s.pdf',
                    (new DateTimeImmutable())
                        ->setTimestamp($_SERVER['REQUEST_TIME'] ?? time())
                        ->setTimeZone(new DateTimeZone('Asia/Tokyo'))
                        ->format('Ymd\THis')
                ),
                'application/pdf',
                false,
                null
            );
            return $form->makePdf();
        }

        return $this->render('index', [
            'form' => $form,
        ]);
    }
}
