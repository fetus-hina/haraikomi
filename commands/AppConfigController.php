<?php

declare(strict_types=1);

namespace app\commands;

use Yii;
use yii\console\Controller;

class AppConfigController extends Controller
{
    public function actionCookie(): int
    {
        $path = Yii::getAlias('@app/config/cookie.php');
        $value = file_exists($path)
            ? require($path)
            : Yii::$app->security->generateRandomString(32);

        echo "<?php\n";
        echo "return \"" . addslashes($value) . "\";\n";
        return 0;
    }
}
