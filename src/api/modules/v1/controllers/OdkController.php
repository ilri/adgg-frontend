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
use backend\modules\core\models\OdkJsonQueue;
use Yii;

class OdkController extends Controller
{
    use JwtAuthTrait;

    public function getUnAuthenticatedActions()
    {
        return ['receive', 'options'];
    }

    public function actionReceive()
    {
        if (Yii::$app->request->isPost) {
            $payload = Yii::$app->request->getBodyParams();
            if (!empty($payload)) {
                $model = new OdkJsonQueue();
                $model->form_data = $payload;
                if ($model->save()) {
                    Yii::info("ODK JSON Form {$model->uuid} successfully saved");
                } else {
                    Yii::error('ODK JSON FORM validation errors: ' . json_encode($model->getErrors()));
                    Yii::info('ODK POST JSON DATA: ' . json_encode($payload));
                }
            }
        } else {
            Yii::info('GET REQUEST DATA:' . json_encode($_GET));
        }

        //Yii::info('Request Host: ' . json_encode(Yii::$app->request->userHost));
        //Yii::info('Request User Agent: ' . json_encode(Yii::$app->request->userAgent));
        //Yii::info('Request User IP: ' . json_encode(Yii::$app->request->userIP));
        //Yii::info('ADGG Server Host Info: ' . json_encode(Yii::$app->request->hostInfo));

        return [
            'success' => true,
        ];
    }
}