<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-01-22 23:54
 * Time: 23:54
 */

namespace backend\modules\reports\models;


use backend\modules\product\models\Product;
use backend\modules\saving\models\Transaction;
use backend\modules\core\TransactionConstants;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use common\widgets\highchart\HighChartInterface;

class SavingTransaction extends Transaction implements HighChartInterface
{
    /**
     * @inheritdoc
     */
    public static function highChartOptions($graphType, $queryOptions)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = static::appendOrgSessionIdCondition($condition, $params);
        $series = [];
        $product_id = $queryOptions['filters']['product_id'] ?? null;
        $transaction_type = TransactionConstants::decodeTransactionType($queryOptions['filters']['transaction_type']);
        if (empty($product_id) && $graphType == HighChart::GRAPH_PIE) {
            $products = Product::getSavingProductsListData();
            foreach ($products as $p_id => $label) {
                list($new_condition, $new_params) = DbUtils::appendCondition('product_id', $p_id, $condition, $params);
                $series[] = [
                    'name' => Lang::t('{product} - {transaction_type}', ['product' => $label, 'transaction_type' => $transaction_type]),
                    'condition' => $new_condition,
                    'params' => $new_params,
                    'sum' => 'amount',
                ];
            }
        } else {
            $series = [
                [
                    'name' => Lang::t('{transaction_type}', ['transaction_type' => $transaction_type]),
                    'condition' => $condition,
                    'params' => $params,
                    'sum' => 'amount',
                ],
            ];
        }
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
     * @throws \yii\base\NotSupportedException
     */
    public static function getDashboardStats($durationType, $sum = false, $filters = [], $dateField = 'transaction_date', $from = null, $to = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = static::appendOrgSessionIdCondition($condition, $params);
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