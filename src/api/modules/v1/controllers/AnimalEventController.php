<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\auth\Session;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Client;
use backend\modules\core\models\Country;
use backend\modules\core\models\Organization;
use common\helpers\DateUtils;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;

class AnimalEventController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = AnimalEvent::class;

    public function init()
    {
        parent::init();
    }


    public function actionEventRead($animal_id = null, $animal_tag_id = null, $country_name = null, $org_name = null, $client_name = null, $pageSize = null, $country_id = null, $org_id = null, $client_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $event_type = null, $from = null, $to = null)
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
        $dateFilter = DateUtils::getDateFilterParams($from, $to, 'event_date', false, false);
        $condition = $dateFilter['condition'];
        $params = [];
        $searchModel = AnimalEvent::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'enablePagination' => true,
            'pageSize' => $pageSize,
            'joinWith' => [
                'animal' => function (\yii\db\ActiveQuery $query) use ($animal_tag_id) {
                    $query->andFilterWhere([Animal::tableName() . '.tag_id' => $animal_tag_id]);
                },
                'country' => function (\yii\db\ActiveQuery $query) use ($country_name) {
                    $query->andFilterWhere(['LIKE', Country::tableName() . '.name', $country_name]);
                },
                'org' => function (\yii\db\ActiveQuery $query) use ($org_name) {
                    $query->andFilterWhere(['LIKE', Organization::tableName() . '.name', $org_name]);
                },
                'client' => function (\yii\db\ActiveQuery $query) use ($client_name) {
                    $query->andFilterWhere(['LIKE', Client::tableName() . '.name', $client_name]);
                },
            ],
            'with' => ['country', 'org', 'client', 'animal', 'region', 'district', 'ward', 'village'],
        ]);

        $searchModel->country_id = $country_id;
        $searchModel->org_id = $org_id;
        $searchModel->client_id = $client_id;
        $searchModel->region_id = $region_id;
        $searchModel->district_id = $district_id;
        $searchModel->ward_id = $ward_id;
        $searchModel->village_id = $village_id;
        $searchModel->event_type = $event_type;
        $searchModel->animal_id = $animal_id;

        if (Session::isPrivilegedAdmin() || Session::isCountryUser() || Session::isOrganizationUser()) {
            return $searchModel->search();
        } else {
            throw new ForbiddenHttpException("Not allowed to access this page");

        }
    }
    public function actionEventWrite($animal_id = null, $animal_tag_id = null, $country_name = null, $org_name = null, $client_name = null, $pageSize = null, $country_id = null, $org_id = null, $client_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $event_type = null, $from = null, $to = null)
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
        $dateFilter = DateUtils::getDateFilterParams($from, $to, 'event_date', false, false);
        $condition = $dateFilter['condition'];
        $params = [];
        $searchModel = AnimalEvent::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'enablePagination' => true,
            'pageSize' => $pageSize,
            'joinWith' => [
                'animal' => function (\yii\db\ActiveQuery $query) use ($animal_tag_id) {
                    $query->andFilterWhere([Animal::tableName() . '.tag_id' => $animal_tag_id]);
                },
                'country' => function (\yii\db\ActiveQuery $query) use ($country_name) {
                    $query->andFilterWhere(['LIKE', Country::tableName() . '.name', $country_name]);
                },
                'org' => function (\yii\db\ActiveQuery $query) use ($org_name) {
                    $query->andFilterWhere(['LIKE', Organization::tableName() . '.name', $org_name]);
                },
                'client' => function (\yii\db\ActiveQuery $query) use ($client_name) {
                    $query->andFilterWhere(['LIKE', Client::tableName() . '.name', $client_name]);
                },
            ],
            'with' => ['country', 'org', 'client', 'animal', 'region', 'district', 'ward', 'village'],
        ]);

        $searchModel->country_id = $country_id;
        $searchModel->org_id = $org_id;
        $searchModel->client_id = $client_id;
        $searchModel->region_id = $region_id;
        $searchModel->district_id = $district_id;
        $searchModel->ward_id = $ward_id;
        $searchModel->village_id = $village_id;
        $searchModel->event_type = $event_type;
        $searchModel->animal_id = $animal_id;

        if (Session::isPrivilegedAdmin() || Session::isCountryUser() || Session::isOrganizationUser()) {
            return $searchModel->search();
        } else {
            throw new ForbiddenHttpException("Not allowed to access this page");

        }
    }
}