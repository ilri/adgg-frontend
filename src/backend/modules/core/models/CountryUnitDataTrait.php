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
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property float $latitude
 * @property float $longitude
 * @property string $latlng
 * @property Country $country
 * @property CountryUnits $region
 * @property CountryUnits $district
 * @property CountryUnits $ward
 * @property CountryUnits $village
 */
trait CountryUnitDataTrait
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
        if (Utils::isWebApp() && Session::isCountry()) {
            if (is_array($condition)) {
                $condition['country_id'] = Session::getCountryId();
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
                list($condition, $params) = DbUtils::appendCondition('country_id', Session::getCountryId(), $condition, $params);
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
        } elseif ($strict && Utils::isWebApp() && !Session::isCountry()) {
            if (is_array($condition)) {
                if (!isset($condition['country_id'])) {
                    $condition['country_id'] = null;
                }
            } else {
                if (!empty($condition)) {
                    $condition .= ' AND ';
                }
                $condition .= '[[country_id]] IS NULL';
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