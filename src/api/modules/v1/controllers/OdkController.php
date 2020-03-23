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
        $payload = Yii::$app->request->getBodyParams();

        return [
            'success'=>true,
            'payload'=>$payload,
        ];
    }
}