<?php

declare(strict_types=1);

namespace app\controllers;

use Curl\Curl;
use Throwable;
use Yii;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
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

    public function actionPostalCode(): Response
    {
        $req = Yii::$app->request;
        $model = DynamicModel::validateData(
            [
                'code' => ($req->isPost ? $req->post('code') : null) ?? $req->get('code'),
            ],
            [
                [['code'], 'required'],
                [['code'], 'string',
                    'skipOnError' => true,
                    'min' => 7,
                    'max' => 7,
                ],
                [['code'], 'match',
                    'skipOnError' => true,
                    'pattern' => '/^[0-9]{7}$/',
                ],
            ]
        );
        if ($model->hasErrors()) {
            return $this->makeInputError($model, ['api/postal-code']);
        }

        $apiResp = $this->requestPostalCodeApi($model->code);
        if ((int)($apiResp['status'] ?? 500) !== 200) {
            return $this->makeResponseError($apiResp, ['api/postal-code']);
        }

        $resp = Yii::$app->response;
        $resp->format = Response::FORMAT_JSON;
        $resp->setStatusCode(200, 'OK');
        $resp->headers->set('Content-Type', 'application/json; charset=UTF-8');
        $resp->headers->set('Content-Language', 'ja');
        $resp->data = ($apiResp['results'] ?? null) ?: [];
        return $resp;
    }

    private function requestPostalCodeApi(string $postalCode): array
    {
        $curl = new Curl();
        $curl->setUserAgent(vsprintf('%s (%s)', [
            'haraikomi-pdf-maker',
            implode('; ', [
                '+https://haraikomi.fetus.jp/',
                '+https://github.com/fetus-hina/haraikomi',
            ]),
        ]));
        $curl->setReferrer(Url::to(['site/index'], true));
        $curl->jsonDecoder = false;
        $curl->xmlDecoder = false;
        $curl->get('https://zip-cloud.appspot.com/api/search', [
            'zipcode' => $postalCode,
            'limit' => '100',
        ]);
        if ($curl->curlError) {
            return [
                'message' => vsprintf('cURLエラー: #%d, %s', [
                    $curl->curlErrorCode,
                    $curl->curlErrorMessage,
                ]),
                'results' => null,
                'status' => 500,
            ];
        } elseif ($curl->httpError) {
            return [
                'message' => vsprintf('リモートAPIエラー: #%d, %s', [
                    $curl->httpStatusCode,
                    $curl->httpErrorMessage,
                ]),
                'results' => null,
                'status' => $curl->httpStatusCode,
            ];
        }

        try {
            return Json::decode($curl->rawResponse, true);
        } catch (Throwable $e) {
            return [
                'message' => vsprintf('JSONデコードエラー: #%d, %s', [
                    $e->getCode(),
                    $e->getMessage(),
                ]),
                'results' => null,
                'status' => 500,
            ];
        }
    }

    private function makeInputError(Model $model, array $url): Response
    {
        $errors = $model->getErrors();
        $firstError = array_values(array_values($errors)[0])[0];
        $invalidParams = [];
        foreach ($errors as $key => $strings) {
            foreach ($strings as $string) {
                $invalidParams[] = [
                    'name' => $key,
                    'reason' => $string,
                ];
            }
        }

        $resp = Yii::$app->response;
        $resp->format = Response::FORMAT_JSON;
        $resp->setStatusCode(400, 'Bad Request');
        $resp->headers->set('Content-Type', 'application/problem+json; charset=UTF-8');
        $resp->headers->set('Content-Language', 'en');
        $resp->data = [
            'type' => 'about:black',
            'title' => 'Your request parameters didn\'t validate.',
            'status' => 400,
            'detail' => $firstError,
            'instance' => Url::to($url, true),
            'invalid-params' => $invalidParams,
        ];
        return $resp;
    }

    private function makeResponseError(array $apiResp, array $url): Response
    {
        $resp = Yii::$app->response;
        $resp->format = Response::FORMAT_JSON;
        $resp->setStatusCode(503, 'Service Unavailable');
        $resp->headers->set('Content-Type', 'application/problem+json; charset=UTF-8');
        $resp->headers->set('Content-Language', 'ja');
        $resp->data = [
            'type' => 'http://zipcloud.ibsnet.co.jp/doc/api',
            'title' => 'リモートAPIエラー',
            'status' => 503,
            'detail' => $apiResp['message'] ?? '(不明なエラー)',
            'instance' => Url::to($url, true),
        ];
        return $resp;
    }
}
