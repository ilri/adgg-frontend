<?php
/**
 * Created by PhpStorm.
 * @author Fred <mconyango@gmail.com>
 * Date: 2018-10-02
 * Time: 12:15
 */

namespace backend\modules\reports\controllers;


use backend\modules\reports\Constants;
use backend\modules\reports\models\Reports;
use backend\modules\reports\models\WarehouseInvoices;
use common\helpers\DateUtils;
use common\helpers\DbUtils;
use Yii;

class ImportCashFlowPlanningController extends Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_REPORTS;
    }

    public function actionIndex($invoice_type = null, $currency = null, $from = null, $to = null)
    {
        $date_filter = DateUtils::getDateFilterParams($from, $to, 'payment_due_date', false, false);
        $report = Reports::loadModel(['code' => Constants::REPORT_IMPORT_CASH_FLOW_PLANNING]);
        $condition = $date_filter['condition'];
        $params = [];
        list($condition, $params) = DbUtils::appendCondition('payment_status', WarehouseInvoices::PAYMENT_STATUS_PAID, $condition, $params, 'AND', '<>');
        $searchModel = WarehouseInvoices::searchModel([
            'enablePagination' => false,
            'condition' => $condition,
            'params' => $params,
            'with' => ['warehouse','warehouse.supplier','warehouse.vessel', 'invoiceType']
        ]);
        $searchModel->invoice_type_id = $invoice_type;
        $searchModel->invoice_currency = $currency;

        if (Yii::$app->request->isAjax) {
            $html = $this->renderAjax('_report', ['model' => $searchModel, 'report' => $report]);
            return json_encode(['html' => $html]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'report' => $report,
            'filterOptions' => [
                'invoice_type' => $invoice_type,
                'currency' => $currency,
                'from' => $date_filter['from'],
                'to' => $date_filter['to'],
            ],
        ]);
    }
}