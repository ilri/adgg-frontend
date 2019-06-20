<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 7:10 PM
 */

namespace backend\modules\core\models;


use backend\modules\auth\Session;
use common\helpers\DbUtils;
use common\helpers\Utils;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Trait OrganizationUnitDataTrait
 * @package backend\modules\core\models
 *
 * @property int $org_id
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property Organization $org
 * @property OrganizationUnits $region
 * @property OrganizationUnits $district
 * @property OrganizationUnits $ward
 * @property OrganizationUnits $village
 */
trait OrganizationUnitDataTrait
{
    /**
     * @param string $condition
     * @param array $params
     * @param bool $strict
     * @return array
     * @throws \Exception
     */
    public static function appendOrgSessionIdCondition($condition = '', $params = [], $strict = false)
    {
        if (Utils::isWebApp() && Session::isOrganization()) {
            if (is_array($condition)) {
                $condition['org_id'] = Session::getOrgId();
                if (Session::isRegionUser()) {
                    $condition['region_id'] = Session::getRegionId();
                } elseif (Session::isDistrictUser()) {
                    $condition['district_id'] = Session::getDistrictId();
                } elseif (Session::isWardUser()) {
                    $condition['ward_id'] = Session::getWardId();
                } elseif (Session::isVillageUser()) {
                    $condition['village_id'] = Session::getVillageId();
                }
            } else {
                list($condition, $params) = DbUtils::appendCondition('org_id', Session::getOrgId(), $condition, $params);
                if (Session::isRegionUser()) {
                    list($condition, $params) = DbUtils::appendCondition('region_id', Session::getRegionId(), $condition, $params);
                } elseif (Session::isDistrictUser()) {
                    list($condition, $params) = DbUtils::appendCondition('district_id', Session::getDistrictId(), $condition, $params);
                } elseif (Session::isWardUser()) {
                    list($condition, $params) = DbUtils::appendCondition('ward_id', Session::getWardId(), $condition, $params);
                } elseif (Session::isVillageUser()) {
                    list($condition, $params) = DbUtils::appendCondition('village_id', Session::getVillageId(), $condition, $params);
                }
            }
        } elseif ($strict && Utils::isWebApp() && !Session::isOrganization()) {
            if (is_array($condition)) {
                if (!isset($condition['org_id'])) {
                    $condition['org_id'] = null;
                }
            } else {
                if (!empty($condition)) {
                    $condition .= ' AND ';
                }
                $condition .= '[[org_id]] IS NULL';
            }
        }
        return [$condition, $params];
    }

    /**
     * @param $condition
     * @param bool $throwException
     * @return $this
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public static function loadModel($condition, $throwException = true)
    {
        $model = parent::loadModel($condition, false);
        if ($model === null) {
            if ($throwException) {
                throw new NotFoundHttpException('The requested resource was not found.');
            }
        } elseif (Utils::isWebApp() && Session::isOrganization()) {
            if ($model->org_id != Session::getOrgId()) {
                throw new ForbiddenHttpException();
            }
            if (Session::isRegionUser() && $model->region_id != Session::getRegionId()) {
                throw new ForbiddenHttpException();
            } elseif (Session::isDistrictUser() && $model->district_id != Session::getDistrictId()) {
                throw new ForbiddenHttpException();
            } elseif (Session::isWardUser() && $model->ward_id != Session::getWardId()) {
                throw new ForbiddenHttpException();
            } elseif (Session::isVillageUser() && $model->village_id != Session::getVillageId()) {
                throw new ForbiddenHttpException();
            }
        }
        return $model;
    }

    /**
     *  {@inheritdoc}
     */
    public static function getListData($valueColumn = 'id', $textColumn = 'name', $prompt = false, $condition = '', $params = [], $options = [])
    {
        list($condition, $params) = static::appendOrgSessionIdCondition($condition, $params, true);

        return parent::getListData($valueColumn, $textColumn, $prompt, $condition, $params, $options);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(Organization::class, ['id' => 'org_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(OrganizationUnits::class, ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(OrganizationUnits::class, ['id' => 'district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWard()
    {
        return $this->hasOne(OrganizationUnits::class, ['id' => 'ward_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillage()
    {
        return $this->hasOne(OrganizationUnits::class, ['id' => 'village_id']);
    }

    /**
     * @return bool
     */
    public function showCountryField(): bool
    {
        return !Session::isOrganization();
    }

    /**
     * @return bool
     */
    public function showRegionField(): bool
    {
        return !Session::isOrganization() || Session::isCountryUser();
    }

    /**
     * @return bool
     */
    public function showDistrictField(): bool
    {
        return !Session::isOrganization() || Session::isCountryUser() || Session::isRegionUser();
    }

    /**
     * @return bool
     */
    public function showWardField(): bool
    {
        return !Session::isOrganization() || Session::isCountryUser() || Session::isRegionUser() || Session::isDistrictUser();
    }

    /**
     * @return bool
     */
    public function showVillageField(): bool
    {
        return !Session::isOrganization() || Session::isCountryUser() || Session::isRegionUser() || Session::isDistrictUser() || Session::isWardUser();
    }
}