<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\auth\Session;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\CountryUnits;
use yii\web\ForbiddenHttpException;

class CountryUnitsController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = CountryUnits::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex($country_id, $level)
    {
        $searchModel = CountryUnits::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'pageSize' => SystemSettings::getPaginationSize(),
            'enablePagination' => true,
        ]);
        $searchModel->country_id = $country_id;
        $searchModel->level = $level;
        if (Session::isPrivilegedAdmin() || Session::isCountryUser() || Session::isOrganizationUser()) {
            return $searchModel->search();
        } else {
            throw new ForbiddenHttpException("Not allowed to access this page");

        }
    }

    public function actionDependentLists($level, $country_id = null, $parent_id = null, $placeholder = false)
    {
        if ($level == CountryUnits::LEVEL_REGION) {
            $data = CountryUnits::getListData('id', 'name', $placeholder, ['country_id' => $country_id, 'level' => $level]);
        } else {
            $data = CountryUnits::getListData('id', 'name', $placeholder, ['parent_id' => $parent_id, 'level' => $level]);
        }
        $response = [];
        foreach ($data as $id => $value) {
            $response[] = [
                'id' => $id,
                'value' => $value,
            ];
        }
        if (Session::isRegionUser() && $level == CountryUnits::LEVEL_REGION) {
            return [$response['id' == Session::getRegionId()]];
        } elseif (Session::isDistrictUser() && $level == CountryUnits::LEVEL_DISTRICT) {
            return [$response['id' == Session::getDistrictId()]];
        } elseif (Session::isDistrictUser() && $level == CountryUnits::LEVEL_WARD) {
            return [$response['id' == Session::getWardId()]];
        } elseif (Session::isDistrictUser() && $level == CountryUnits::LEVEL_VILLAGE) {
            return [$response['id' == Session::getVillageId()]];
        }
        return $response;
    }

}