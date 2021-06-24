<?php

declare(strict_types=1);

namespace app\actions\api;

use JsonException;
use Throwable;
use Yii;
use app\models\PostalCodeApiForm;
use yii\base\Action;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\httpclient\Client as HttpClient;
use yii\web\Response;

final class PostalCodeAction extends Action
{
    private const CONTACT_URL_GITHUB = 'https://github.com/fetus-hina/haraikomi';
    private const CONTACT_URL_WEBSITE = 'https://haraikomi.fetus.jp/';
    private const ZIPCLOUD_API_ENDPOINT = 'https://zip-cloud.appspot.com/api/search';
    private const ZIPCLOUD_URL = 'http://zipcloud.ibsnet.co.jp/doc/api';

    public function run(): Response
    {
        $req = Yii::$app->request;
        $model = Yii::createObject(PostalCodeApiForm::class);
        $model->attributes = $_POST;
        if (!$model->validate()) {
            return $this->makeInputError($model, ['api/postal-code']);
        }

        // @phpstan-ignore-next-line
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
        try {
            $request = Yii::createObject(HttpClient::class)
                ->createRequest()
                ->addHeaders([
                    'Referer' => Url::to(['site/index'], true),
                    'User-Agent' => vsprintf('%s (%s)', [
                        'haraikomi-pdf-maker',
                        implode('; ', [
                            '+' . self::CONTACT_URL_WEBSITE,
                            '+' . self::CONTACT_URL_GITHUB,
                        ]),
                    ]),
                ])
                ->setFormat(HttpClient::FORMAT_RAW_URLENCODED)
                ->setUrl(self::ZIPCLOUD_API_ENDPOINT)
                ->setData([
                    'zipcode' => $postalCode,
                    'limit' => '100',
                ]);

            $response = $request->send();
            if (!$response->getIsOk()) {
                return [
                    'message' => vsprintf('リモートAPIエラー: #%d', [
                        $response->getStatusCode(),
                    ]),
                    'results' => null,
                    'status' => $response->getStatusCode(),
                ];
            }

            try {
                $json = $response->getData();
            } catch (InvalidArgumentException $e) {
                throw new JsonException($e->getMessage(), $e->getCode(), $e);
            }

            if (!is_array($json)) {
                return [
                    'message' => 'リモートAPIエラー: 返却形式異常',
                    'results' => null,
                    'status' => 500,
                ];
            }

            if (!isset($json['status']) || (int)$json['status'] !== 200) {
                return [
                    'message' => vsprintf('リモートAPIエラー: #%d, %s', [
                        (int)($json['status'] ?? -1),
                        $json['message'] ?? '(null)',
                    ]),
                    'results' => null,
                    'status' => (int)($json['status'] ?? 500),
                ];
            }

            return $json;
        } catch (JsonException $e) {
            return [
                'message' => vsprintf('JSONデコードエラー: #%d, %s', [
                    $e->getCode(),
                    $e->getMessage(),
                ]),
                'results' => null,
                'status' => 500,
            ];
        } catch (Throwable $e) {
            return [
                'message' => vsprintf('API処理エラー: #%d, %s', [
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
            'type' => 'about:blank',
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
            'type' => self::ZIPCLOUD_URL,
            'title' => 'リモートAPIエラー',
            'status' => 503,
            'detail' => $apiResp['message'] ?? '(不明なエラー)',
            'instance' => Url::to($url, true),
        ];
        return $resp;
    }
}
