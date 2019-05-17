<?php
/**
 * Created by PhpStorm.
 * @author Fred <mconyango@gmail.com>
 * Date: 2018-09-26
 * Time: 01:21
 */

namespace backend\modules\reports\controllers;


use backend\modules\reports\models\Warehouse;
use backend\modules\reports\Constants;
use backend\modules\reports\models\Reports;
use common\helpers\DateUtils;
use common\helpers\DbUtils;
use Yii;

class ProductAgingController extends Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_REPORTS;
    }

    public function actionIndex($vessel_id = null, $discharge_terminal_id = null, $product_id = null, $aging_batch = null, $from = null, $to = null)
    {
        $date_filter = DateUtils::getDateFilterParams($from, $to, 'end_of_discharge_date', false, false);
        $report = Reports::loadModel(['code' => Constants::REPORT_PRODUCT_AGING]);
        $condition = $date_filter['condition'];
        $params = [];
        list($condition, $params) = DbUtils::appendCondition('available_volume', 0, $condition, $params, 'AND', '>');
        list($condition, $params) = Warehouse::addAgingBatchQueryCondition($aging_batch, $condition, $params);
        $searchModel = Warehouse::searchModel([
            'enablePagination' => false,
            'condition' => $condition,
            'params' => $params,
            'with' => ['vessel', 'dischargeTerminal', 'product']
        ]);
        $searchModel->vessel_id = $vessel_id;
        $searchModel->discharge_terminal_id = $discharge_terminal_id;
        $searchModel->product_id = $product_id;
        if (Yii::$app->request->isAjax) {
            $html = $this->renderAjax('_report', ['model' => $searchModel, 'report' => $report]);
            return json_encode(['html' => $html]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'report' => $report,
            'filterOptions' => [
                'vessel_id' => $vessel_id,
                'discharge_terminal_id' => $discharge_terminal_id,
                'product_id' => $product_id,
                'aging_batch' => $aging_batch,
                'from' => $date_filter['from'],
                'to' => $date_filter['to'],
            ],
        ]);
    }
}