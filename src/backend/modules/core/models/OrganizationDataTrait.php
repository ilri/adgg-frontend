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
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Trait OrganizationDataTrait
 * @package backend\modules\core\models
 *
 * @property Organization $org
 */
trait OrganizationDataTrait
{
    /**
     * @param string $condition
     * @param array $params
     * @param bool $strict
     * @param string $orgIdAttribute
     * @return array
     * @throws \Exception
     */
    public static function appendOrgSessionIdCondition($condition = '', $params = [], $strict = false, $orgIdAttribute = 'org_id')
    {
        if (Utils::isWebApp() && Session::isOrganization()) {
            if (is_array($condition)) {
                $condition[$orgIdAttribute] = Session::getOrgId();
            } else {
                list($condition, $params) = DbUtils::appendCondition($orgIdAttribute, Session::getOrgId(), $condition, $params);
            }
        } elseif ($strict && Utils::isWebApp() && !Session::isOrganization()) {
            if (is_array($condition)) {
                if (!isset($condition[$orgIdAttribute])) {
                    $condition[$orgIdAttribute] = null;
                }
            } else {
                if (!empty($condition)) {
                    $condition .= ' AND ';
                }
                $condition .= '[[' . $orgIdAttribute . ']] IS NULL';
            }
        }
        return [$condition, $params];
    }

    /**
     * @param $condition
     * @param bool $throwException
     * @param string $orgIdAttribute
     * @return $this
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public static function loadModel($condition, $throwException = true, $orgIdAttribute = 'org_id')
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
        } elseif (Utils::isWebApp() && Session::isOrganization() && $model->{$orgIdAttribute} != Session::getOrgId()) {
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
    public function getOrg()
    {
        return $this->hasOne(Organization::class, ['id' => 'org_id']);
    }
}