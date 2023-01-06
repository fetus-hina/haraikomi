<?php

declare(strict_types=1);

namespace app\commands;

use Exception;
use Yii;
use app\models\DestPreset;
use app\models\JpBankHtml;
use app\models\JpGienkin;
use yii\console\Controller;
use yii\helpers\Json;

use function fwrite;
use function mb_convert_kana;
use function min;
use function stream_get_contents;
use function strtotime;

use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;
use const STDERR;
use const STDIN;

final class JpBankController extends Controller
{
    public function actionParse(): int
    {
        $model = Yii::createObject([
            'class' => JpBankHtml::class,
            'html' => stream_get_contents(STDIN),
        ]);
        if (!$model->validate()) {
            fwrite(STDERR, Json::encode($model->getErrors()) . "\n");
            return 1;
        }

        $data = [];
        foreach ($model->parse() as $row) {
            $data[] = $row->json;
        }

        echo Json::encode(
            $data,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
        );
        echo "\n";
        return 0;
    }

    public function actionParseAndImport(): int
    {
        $parser = Yii::createObject([
            'class' => JpBankHtml::class,
            'html' => stream_get_contents(STDIN),
        ]);
        if (!$parser->validate()) {
            fwrite(STDERR, Json::encode($parser->getErrors()) . "\n");
            return 1;
        }

        return Yii::$app->db->transaction(function () use ($parser): int {
            $availableIds = [];

            foreach ($parser->parse() as $row) {
                $tS = strtotime($row->start->format('Y-m-d') . 'T00:00:00+09:00');
                $tE = strtotime($row->end->format('Y-m-d') . 'T24:00:00+09:00');
                if ($tS === false || $tE === false) {
                    throw new Exception();
                }

                $jpGienkin = $this->getOrCreateJpGienkinModel($row->disaster, $tS);
                $preset = $this->getOrCreateDestPreset($row->account[0], $row->account[1], $row->account[2]);
                $preset->name = mb_convert_kana($row->accountName, 'asKV', 'UTF-8');
                $preset->account_name = mb_convert_kana($row->accountName, 'ASKV', 'UTF-8');
                $preset->valid_from = $tS;
                $preset->valid_to = $tE;
                $preset->jp_gienkin_id = $jpGienkin->id;
                if (!$preset->save()) {
                    throw new Exception();
                }
                $availableIds[] = $preset->id;
            }

            if (!$availableIds) {
                fwrite(STDERR, "No items?\n");
                return 1;
            }

            // ページから記載が消えたものを削除する
            DestPreset::deleteAll(['and',
                ['not', ['jp_gienkin_id' => null]],
                ['not', ['id' => $availableIds]],
            ]);

            return 0;
        });
    }

    private function getOrCreateJpGienkinModel(string $name, int $refTime): JpGienkin
    {
        if (!$model = JpGienkin::findOne(['name' => $name])) {
            $model = Yii::createObject([
                'class' => JpGienkin::class,
                'name' => $name,
                'ref_time' => $refTime,
            ]);
        }

        $model->ref_time = min($model->ref_time, $refTime);
        $model->save();
        return $model;
    }

    private function getOrCreateDestPreset(int $a1, int $a2, int $a3): DestPreset
    {
        $model = DestPreset::findOne([
            'account1' => $a1,
            'account2' => $a2,
            'account3' => $a3,
        ]);
        if (!$model) {
            $model = Yii::createObject([
                'class' => DestPreset::class,
                'account1' => $a1,
                'account2' => $a2,
                'account3' => $a3,
            ]);
        }
        return $model;
    }
}
