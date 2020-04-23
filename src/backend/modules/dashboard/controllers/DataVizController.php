<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-04-19
 * Time: 12:04 PM
 */

namespace backend\modules\dashboard\controllers;


use backend\modules\auth\Session;
use backend\modules\core\models\Country;
use common\helpers\Url;

class DataVizController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        if (Session::isPrivilegedAdmin() || Session::isCountryUser() || Session::isOrganizationUser()){
            $country_id = \Yii::$app->request->get('country_id');
            $org_id = \Yii::$app->request->get('org_id');
            return $this->render('index', [
                'filterOptions' => [
                    'country_id' => $country_id,
                    'org_id' => $org_id,
                ]
            ]);
        }
        return $this->redirect(Url::to(['/dashboard/default']));
    }

    public function actionLoadChart($name)
    {
        $country_id = \Yii::$app->request->get('country_id');
        $org_id = \Yii::$app->request->get('org_id');
        return $this->renderAjax('partials/'. $name, [
            'filterOptions' => [
                'country_id' => $country_id,
                'org_id' => $org_id,
            ],
        ]);
    }

}