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

class DefaultController extends Controller
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
            return $this->redirect(Url::to(['/dashboard/data-viz']));
        }
        $countries = Country::find()->orderBy(['code' => SORT_ASC])->all();
        return $this->render('index2', [
            'countries' => $countries,
        ]);
    }

    public function actionGraph($graphType = null, $dateRange = null, $animal_type = null, $main_breed = null, $country_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null)
    {
        return $this->renderPartial('graph/_widget', [
            'graphType' => $graphType,
            'dateRange' => $dateRange,
            'graphFilterOptions' => [
                'animal_type' => $animal_type,
                'main_breed' => $main_breed,
                'country_id' => $country_id,
                'region_id' => $region_id,
                'district_id' => $district_id,
                'ward_id' => $ward_id,
                'village_id' => $village_id,
            ],
        ]);
    }
}