<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-23
 * Time: 3:32 AM
 */

namespace api\modules\v1\controllers;


use api\controllers\Controller;
use api\controllers\JwtAuthTrait;
use Yii;

class OdkWebHookController extends Controller
{
    use JwtAuthTrait;

    protected function getUnAuthenticatedActions()
    {
        return ['receive'];
    }

    public function actionReceive()
    {
        $json = null;
        if (Yii::$app->request->isPost) {
            $json = Yii::$app->request->post();
        } else {
            $json = Yii::$app->request->get();
        }
        return [
            'json' => $json,
            'hostInfo' => Yii::$app->request->hostInfo,
        ];
    }
}