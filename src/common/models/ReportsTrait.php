<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-11-06
 * Time: 6:50 PM
 */

namespace common\models;


use common\helpers\DbUtils;

trait ReportsTrait
{
    /**
     * @param integer $durationType
     * @param bool|string $sum
     * @param array $filters array key=>$value pair where key is the attribute name and value is the attribute value
     * @param string $dateField
     * @param null|string $from
     * @param null|string $to
     * @param mixed $condition
     * @param array $params
     * @return int
     * @throws \Exception
     */
    public static function getDashboardStats($durationType, $sum = false, $filters = [], $dateField = 'created_at', $from = null, $to = null, $condition = '', $params = [])
    {
        if (!empty($filters)) {
            foreach ($filters as $k => $v) {
                if (!(empty($v) && strlen($v) == 0)) {
                    list($condition, $params) = DbUtils::appendCondition($k, $v, $condition, $params);
                }
            }
        }
        return static::getStats($durationType, $condition, $params, $sum, $dateField, $from, $to);
    }

    public function reportBuilderUnwantedFields()
    {
        return [
            'country_id', 'test', 'additional_attributes', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_active', 'is_deleted', 'deleted_at', 'deleted_by',
            'password', 'password_hash', 'password_reset_token', 'profile_image', 'account_activation_token', 'auth_key', 'auto_generate_password',
            'is_main_account', 'last_login', 'level_id', 'require_password_change', 'role_id', 'timezone',
            'event_type',
        ];
    }

    public function reportBuilderFields()
    {
        $unwanted = array_merge($this->reportBuilderUnwantedFields(), $this->reportBuilderAdditionalUnwantedFields());
        $attributes = $this->attributes();
        $attrs = array_diff($attributes, $unwanted);
        sort($attrs);
        return $attrs;
    }

    /**
     * @return array
     * This can be used to add Model Specific unwanted fields
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return [];
    }
}