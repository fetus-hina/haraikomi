<?php

namespace app\controllers;

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
            $resp->headers->set('Content-Type', 'application/pdf');
            return $form->makePdf();
        }

        return $this->render('index', [
            'form' => $form,
        ]);
    }
}
