<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-14
 * Time: 3:36 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Session;
use common\models\ActiveRecord;

trait SessionTrait
{
    /**
     * @param ActiveRecord $searchModel
     * @param int|null $org_id
     * @param int|null $region_id
     * @param int|null $district_id
     * @param int|null $ward_id
     * @param int|null $village_id
     * @return ActiveRecord
     */
    public function setSessionData($searchModel, $org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null)
    {
        if (Session::isOrganization()) {
            $org_id = Session::getOrgId();
        }
        if (Session::isRegionUser()) {
            $region_id = Session::getRegionId();
        } elseif (Session::isDistrictUser()) {
            $district_id = Session::getDistrictId();
        } elseif (Session::isWardUser()) {
            $ward_id = Session::getWardId();
        } elseif (Session::isVillageUser()) {
            $village_id = Session::getVillageId();
        }

        $searchModel->org_id = $org_id;
        $searchModel->region_id = $region_id;
        $searchModel->district_id = $district_id;
        $searchModel->ward_id = $ward_id;
        $searchModel->village_id = $village_id;

        return $searchModel;
    }
}