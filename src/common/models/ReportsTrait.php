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
}