<?php

declare(strict_types=1);

namespace app\controllers;

use DateTimeImmutable;
use DateTimeZone;
use Yii;
use app\models\HaraikomiForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * @return Array<string, string|array>
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'clear-opcache',
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'ips' => [
                            '127.0.0.0/8',
                            '::1',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /** @return Response|string */
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

    public function actionClearOpcache(): string
    {
        $r = Yii::$app->response;
        $r->format = Response::FORMAT_RAW;
        $r->headers->set('Content-Type', 'text/plain; charset=UTF-8');

        if (function_exists('opcache_reset')) {
            opcache_reset();
            return 'ok';
        }

        $r->statusCode = 501;
        return 'not ok';
    }
}
