<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-23
 * Time: 3:21 AM
 */

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\auth\Session;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\Farm;

class FarmersController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = Farm::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex($country_id = null, $org_id = null, $client_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $farm_name = null, $farmer_phone = null, $farm_type = null, $project = null)
    {
        $country_id = Session::getCountryId($country_id);
        $org_id = Session::getOrgId($org_id);
        $client_id = Session::getClientId($client_id);
        $region_id = Session::getRegionId($region_id);
        $district_id = Session::getDistrictId($district_id);
        $ward_id = Session::getWardId($ward_id);
        $village_id = Session::getVillageId($village_id);
        $condition = '';
        $params = [];
        $searchModel = Farm::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'pageSize' => SystemSettings::getPaginationSize(),
            'enablePagination' => true,
            'condition' => $condition,
            'params' => $params,
            'with' => ['country', 'org', 'client', 'region', 'district', 'ward', 'village', 'fieldAgent'],
        ]);
        $searchModel->country_id = $country_id;
        $searchModel->org_id = $org_id;
        $searchModel->client_id = $client_id;
        $searchModel->region_id = $region_id;
        $searchModel->district_id = $district_id;
        $searchModel->ward_id = $ward_id;
        $searchModel->village_id = $village_id;
        $searchModel->name = $farm_name;
        $searchModel->phone = $farmer_phone;
        $searchModel->farm_type = $farm_type;
        $searchModel->project = $project;
        return $searchModel->search();
    }
}