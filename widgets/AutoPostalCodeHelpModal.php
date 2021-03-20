<?php

declare(strict_types=1);

namespace app\widgets;

use Yii;
use cebe\markdown\GithubMarkdown;
use yii\helpers\Html;

class AutoPostalCodeHelpModal extends Modal
{
    public static function getModalId(): string
    {
        return 'modal-postalcode-help';
    }

    protected function getTitleText(): string
    {
        return '住所入力機能について';
    }

    protected function renderBodyData(): string
    {
        $map = [
            '{apiCompany}' => '株式会社アイビス',
            '{appAuthor}' => '相沢陽菜',
            '{appName}' => Yii::$app->name,
        ];

        $md = trim(str_replace(
            array_keys($map),
            array_values($map),
            file_get_contents(__FILE__, false, null, __COMPILER_HALT_OFFSET__)
        ));

        $mdParser = new class () extends GithubMarkdown {
            /** @param mixed $block */
            protected function renderLink($block): string
            {
                if (isset($block['refkey'])) {
                    if (($ref = $this->lookupReference($block['refkey'])) !== false) {
                        $block = array_merge($block, $ref);
                    } else {
                        if (strncmp($block['orig'], '[', 1) === 0) {
                            return vsprintf('[%s', [
                                $this->renderAbsy($this->parseInline(substr($block['orig'], 1))),
                            ]);
                        }

                        return $block['orig'];
                    }
                }

                $attrs = [
                    'title' => !empty($block['title']) ? $block['title'] : null,
                    'target' => '_blank',
                ];

                return Html::a(
                    $this->renderAbsy($block['text']),
                    $block['url'],
                    $attrs
                );
            }
        };
        $mdParser->html5 = true;
        return preg_replace('/\n+/', '', $mdParser->parse($md));
    }
}

// phpcs:disable

__halt_compiler();
郵便番号に対応する住所を取得するため、**{apiCompany}の提供する[検索API](http://zipcloud.ibsnet.co.jp/doc/api)を利用**しています。

「住所入力」ボタンを押すと、このサイト（{appName}）のサーバから住所の問い合わせが行われます。  
（このサイトのサーバを経由するため、あなたのIPアドレスやブラウザの情報は**直接{apiCompany}のサーバには伝わりません**）

APIサーバへは郵便番号のみが伝えられますので、あなたの個人情報としての「価値」は低い状態になっていますが、**その郵便番号の人が、このサイトを使用していることは伝わってしまいます。**

また、当然ですが**このサイト（{appName}）の運営者（{appAuthor}）にあなたの郵便番号、IPアドレス等の情報が送信されます。**  
運営者はそれらの情報を（少なくとも意図的には）保存しておらず、何か（例えばマーケティング）に転用するようなことはしません。

なお、運営者（{appAuthor}）と{apiCompany}の間には一切の関係は存在しません。

「住所入力」ボタンを押した場合、

   1. あなたが、**このサイト（{appName}）の運営者（{appAuthor}）に何が伝わるか理解している**こと
   2. あなたが、**{apiCompany}に何が伝わるか理解している**こと
   3. あなたが、それらによって**何が起きる可能性があるか理解して**おり、その**リスクを受け入れる**こと

を満たしているものとみなします。
