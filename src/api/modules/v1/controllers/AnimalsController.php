<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\auth\Session;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use yii\web\ForbiddenHttpException;

class AnimalsController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = Animal::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex($pageSize = null, $country_id = null, $org_id = null, $client_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $farm_name = null, $tag_id = null, $animal_type = null, $breed = null)
    {
        $country_id = Session::getCountryId($country_id);
        $org_id = Session::getOrgId($org_id);
        $client_id = Session::getClientId($client_id);
        $region_id = Session::getRegionId($region_id);
        $district_id = Session::getDistrictId($district_id);
        $ward_id = Session::getWardId($ward_id);
        $village_id = Session::getVillageId($village_id);
        if ($pageSize == null) {
            $pageSize = SystemSettings::getPaginationSize();
        }
        $condition = '';
        $params = [];
        $searchModel = Animal::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'enablePagination' => true,
            'condition' => $condition,
            'params' => $params,
            'pageSize' => $pageSize,
            'joinWith' => [
                'farm' => function (\yii\db\ActiveQuery $query) use ($farm_name) {
                    $query->andFilterWhere(['LIKE', Farm::tableName() . '.name', $farm_name]);
                },
            ],
            'with' => ['country', 'org', 'client', 'farm', 'region', 'district', 'ward', 'village'],
        ]);
        $searchModel->country_id = $country_id;
        $searchModel->org_id = $org_id;
        $searchModel->client_id = $client_id;
        $searchModel->region_id = $region_id;
        $searchModel->district_id = $district_id;
        $searchModel->ward_id = $ward_id;
        $searchModel->village_id = $village_id;
        $searchModel->tag_id = $tag_id;
        $searchModel->animal_type = $animal_type;
        $searchModel->main_breed = $breed;
        if (Session::isPrivilegedAdmin() || Session::isCountryUser() || Session::isOrganizationUser()) {
            return $searchModel->search();
        } else {
            throw new ForbiddenHttpException("Not allowed to access this page");

        }
    }
}