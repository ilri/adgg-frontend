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
use common\models\ActiveRecord;
use yii\db\Expression;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Trait OrganizationRefUnitDataTrait
 * @package backend\modules\core\models
 *
 * @property int $country_id
 * @property int $org_id
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property float $latitude
 * @property float $longitude
 * @property string $latlng
 * @property CountryUnits $region
 * @property CountryUnits $district
 * @property CountryUnits $ward
 * @property CountryUnits $village
 *
 * @property Country $country
 * @property Organization $org
 * @property Client $client
 */
trait CountryUnitDataTrait
{
    /**
     * @param string $condition
     * @param array $params
     * @param bool $strict
     * @param string|null $tableName
     * @return array
     * @throws \Exception
     */
    public static function appendOrgSessionIdCondition($condition = '', $params = [], $strict = false, $tableName = null)
    {
        if (Utils::isWebApp() && Session::isCountry()) {
            if (is_array($condition)) {
                $condition[($tableName) ? $tableName.'.country_id' : 'country_id'] = Session::getCountryId();
                if (Session::isOrganizationUser()) {
                    $condition[($tableName) ? $tableName.'.org_id' : 'org_id'] = Session::getOrgId();
                } elseif (Session::isOrganizationClientUser()) {
                    $condition[($tableName) ? $tableName.'.client_id' : 'client_id'] = Session::getClientId();
                } elseif (Session::isRegionUser()) {
                    $condition[($tableName) ? $tableName.'.region_id' : 'region_id'] = Session::getRegionId();
                } elseif (Session::isDistrictUser()) {
                    $condition[($tableName) ? $tableName.'.district_id' : 'district_id'] = Session::getDistrictId();
                } elseif (Session::isWardUser()) {
                    $condition[($tableName) ? $tableName.'.ward_id' : 'ward_id'] = Session::getWardId();
                } elseif (Session::isVillageUser()) {
                    $condition[($tableName) ? $tableName.'.village_id' : 'village_id'] = Session::getVillageId();
                }
            } else {
                list($condition, $params) = DbUtils::appendCondition(($tableName) ? $tableName.'.[[country_id]]' : 'country_id', Session::getCountryId(), $condition, $params);
                if (Session::isOrganizationUser()) {
                    list($condition, $params) = DbUtils::appendCondition(($tableName) ? $tableName.'.[[org_id]]' :'org_id', Session::getOrgId(), $condition, $params);
                } elseif (Session::isOrganizationClientUser()) {
                    list($condition, $params) = DbUtils::appendCondition(($tableName) ? $tableName.'.[[client_id]]' :'client_id', Session::getClientId(), $condition, $params);
                } elseif (Session::isRegionUser()) {
                    list($condition, $params) = DbUtils::appendCondition(($tableName) ? $tableName.'.[[region_id]]' :'region_id', Session::getRegionId(), $condition, $params);
                } elseif (Session::isDistrictUser()) {
                    list($condition, $params) = DbUtils::appendCondition(($tableName) ? $tableName.'.[[district_id]]' :'district_id', Session::getDistrictId(), $condition, $params);
                } elseif (Session::isWardUser()) {
                    list($condition, $params) = DbUtils::appendCondition(($tableName) ? $tableName.'.[[ward_id]]' :'ward_id', Session::getWardId(), $condition, $params);
                } elseif (Session::isVillageUser()) {
                    list($condition, $params) = DbUtils::appendCondition(($tableName) ? $tableName.'.[[village_id]]' :'village_id', Session::getVillageId(), $condition, $params);
                }
            }
        } elseif ($strict && Utils::isWebApp() && !Session::isCountry()) {
            if (is_array($condition)) {
                if (!isset($condition['country_id'])) {
                    $condition[($tableName) ? $tableName.'.country_id' : 'country_id'] = null;
                }
            } else {
                if (!empty($condition)) {
                    $condition .= ' AND ';
                }
                $condition .= ($tableName) ? $tableName.'.[[country_id]] IS NULL' : '[[country_id]] IS NULL';
            }
        }
        return [$condition, $params];
    }

    /**
     * @param $condition
     * @param bool $throwException
     * @return $this|ActiveRecord
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public static function loadModel($condition, $throwException = true)
    {
        if (is_string($condition) && !is_numeric($condition)) {
            $model = parent::loadModel(['uuid' => $condition], false);
        } else {
            $model = parent::loadModel($condition, false);
        }
        if ($model === null) {
            if ($throwException) {
                throw new NotFoundHttpException('The requested resource was not found.');
            }
        } elseif (Utils::isWebApp() && Session::isCountry()) {
            if ($model->country_id != Session::getCountryId()) {
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
        list($condition, $params) = static::appendOrgSessionIdCondition($condition, $params, false);

        return parent::getListData($valueColumn, $textColumn, $prompt, $condition, $params, $options);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(Organization::class, ['id' => 'org_id']);
    }

    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(CountryUnits::class, ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(CountryUnits::class, ['id' => 'district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWard()
    {
        return $this->hasOne(CountryUnits::class, ['id' => 'ward_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillage()
    {
        return $this->hasOne(CountryUnits::class, ['id' => 'village_id']);
    }

    /**
     * @return bool
     */
    public function showCountryField(): bool
    {
        return !Session::isCountry();
    }

    /**
     * @return bool
     */
    public function showRegionField(): bool
    {
        return !Session::isCountry() || Session::isCountryUser();
    }

    /**
     * @return bool
     */
    public function showDistrictField(): bool
    {
        return !Session::isCountry() || Session::isCountryUser() || Session::isRegionUser();
    }

    /**
     * @return bool
     */
    public function showWardField(): bool
    {
        return !Session::isCountry() || Session::isCountryUser() || Session::isRegionUser() || Session::isDistrictUser();
    }

    /**
     * @return bool
     */
    public function showVillageField(): bool
    {
        return !Session::isCountry() || Session::isCountryUser() || Session::isRegionUser() || Session::isDistrictUser() || Session::isWardUser();
    }

    protected function setLocationData()
    {
        if (($this->hasAttribute('latitude') && !empty($this->latitude)) && ($this->hasAttribute('longitude') && !empty($this->longitude))) {
            $this->latlng = new Expression("ST_GeomFromText('POINT({$this->latitude} {$this->longitude})')");
        }
    }
}