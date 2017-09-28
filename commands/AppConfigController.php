<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class AppConfigController extends Controller
{
    public function actionCookie() : int
    {
        $value = Yii::$app->security->generateRandomString(32);
        echo "<?php\n";
        echo "return \"" . addslashes($value) . "\";\n";
        return 0;
    }
}
