<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-03-23
 * Time: 3:31 PM
 */

namespace api\modules\v1\controllers;


use api\controllers\Controller;
use api\controllers\JwtAuthTrait;
use Yii;

class OdkController extends Controller
{
    use JwtAuthTrait;

    public function getUnAuthenticatedActions()
    {
        return ['receive'];
    }

    public function actionReceive()
    {
        if (Yii::$app->request->isPost) {
            $payload = Yii::$app->request->getBodyParams();
            Yii::info('ODK POST JSON DATA: ' . json_encode($payload));
            Yii::info('$_POST: ' . json_encode($_POST));
            Yii::info('Request Host: ' . json_encode(Yii::$app->request->userHost));
            Yii::info('Request User Agent: ' . json_encode(Yii::$app->request->userAgent));
            Yii::info('Request User IP: ' . json_encode(Yii::$app->request->userIP));
        } else {
            Yii::info('GET REQUEST DATA:' . json_encode($_GET));
        }

        return [
            'success' => true,
            'request' => Yii::$app->request,
            'isGet' => Yii::$app->request->isGet,
            'isPost' => Yii::$app->request->isPost,
        ];
    }
}