<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-12-04 12:37
 * Time: 12:37
 */

namespace backend\modules\core\models;


use backend\modules\auth\Session;
use common\helpers\DbUtils;
use common\helpers\Utils;
use common\models\ActiveRecord;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Trait OrganizationRefDataTrait
 * @package backend\modules\core\models
 *
 * @property Country $country
 */
trait CountryDataTrait
{
    /**
     * @param string $condition
     * @param array $params
     * @param bool $strict
     * @param string $countryIdAttribute
     * @return array
     * @throws \Exception
     */
    public static function appendOrgSessionIdCondition($condition = '', $params = [], $strict = false, $countryIdAttribute = 'country_id')
    {
        if (Utils::isWebApp() && Session::isCountry()) {
            if (is_array($condition)) {
                $condition[$countryIdAttribute] = Session::getCountryId();
            } else {
                list($condition, $params) = DbUtils::appendCondition($countryIdAttribute, Session::getCountryId(), $condition, $params);
            }
        } elseif ($strict && Utils::isWebApp() && !Session::isCountry()) {
            if (is_array($condition)) {
                if (!isset($condition[$countryIdAttribute])) {
                    $condition[$countryIdAttribute] = null;
                }
            } else {
                if (!empty($condition)) {
                    $condition .= ' AND ';
                }
                $condition .= '[[' . $countryIdAttribute . ']] IS NULL';
            }
        }
        return [$condition, $params];
    }

    /**
     * @param $condition
     * @param bool $throwException
     * @param string $countryIdAttribute
     * @return $this|ActiveRecord
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public static function loadModel($condition, $throwException = true, $countryIdAttribute = 'country_id')
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
        } elseif (Utils::isWebApp() && Session::isCountry() && $model->{$countryIdAttribute} != Session::getCountryId()) {
            throw new ForbiddenHttpException();
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
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }
}