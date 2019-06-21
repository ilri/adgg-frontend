<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-20
 * Time: 9:40 AM
 */

namespace backend\modules\reports\models;


use backend\modules\core\models\Farm;
use common\helpers\DbUtils;
use common\helpers\Str;
use common\widgets\highchart\HighChart;
use common\widgets\highchart\HighChartInterface;

class FarmReport extends Farm implements HighChartInterface
{
    /**
     * @inheritdoc
     */
    public static function highChartOptions($graphType, $queryOptions)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = static::appendOrgSessionIdCondition($condition, $params);
        $series = [
            [
                'name' =>'Total Farms',
                'condition' => $condition,
                'params' => $params,
                'sum' => false,
            ],
        ];
        if ($graphType !== HighChart::GRAPH_PIE) {
            return $series;
        } else {
            return [
                [
                    'data' => $series,
                ]
            ];
        }
    }

    /**
     * @param integer $durationType
     * @param bool|string $sum
     * @param array $filters array key=>$value pair where key is the attribute name and value is the attribute value
     * @param string $dateField
     * @param null|string $from
     * @param null|string $to
     * @return int
     * @throws \Exception
     */
    public static function getDashboardStats($durationType, $sum = false, $filters = [], $dateField = 'reg_date', $from = null, $to = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = static::appendOrgSessionIdCondition($condition, $params);
        if (!empty($filters)) {
            foreach ($filters as $k => $v) {
                if (!Str::isEmpty($v)) {
                    list($condition, $params) = DbUtils::appendCondition($k, $v, $condition, $params);
                }
            }
        }

        return static::getStats($durationType, $condition, $params, $sum, $dateField, $from, $to);
    }
}