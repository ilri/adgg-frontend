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
            Yii::info('ODK POST DATA: ' . json_encode($_POST));
        } else {
            Yii::info('GET REQUEST DATA:' . json_encode($_GET));
        }


        return [
            'success' => true,
        ];
    }
}