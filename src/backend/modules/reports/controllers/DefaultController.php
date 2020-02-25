<?php

namespace backend\modules\reports\controllers;

use backend\modules\auth\Acl;
use backend\modules\reports\models\AdhocReport;
use backend\modules\reports\models\Reports;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\helpers\Url;
use console\jobs\ReportGenerator;
use Yii;
use yii\helpers\Json;

/**
 * Default controller for the `reports` module
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resource = \backend\modules\core\Constants::RES_REPORT_BUILDER;
        $this->hasPrivilege(Acl::ACTION_CREATE);
    }

    public function actionIndex($country_id)
    {
        return $this->render('index',[
            'country_id' => $country_id,
        ]);
    }

    public function actionView($type, $country_id){
        $from = null; $to = null;
        $date_filter = DateUtils::getDateFilterParams($from, $to, 'created_at', false, true);

        return $this->render('view',[
            'type' => $type,
            'country_id' => $country_id,
            'filterOptions' => [
                'region_id' => null,
                'district_id' => null,
                'ward_id' => null,
                'village_id' => null,
                'dateFilterFrom' => $date_filter['from'],
                'dateFilterTo' => $date_filter['to'],
            ],
        ]);
    }

    public function actionGenerate($type){
        $name = '';
        switch ($type){
            case 'milkdata':
                $name = 'Milk_data';
                $builder = Reports::milkDataReport(\Yii::$app->request->post());
                $query = null;
                break;
            case 'pedigree':
                $name = 'Pedigree';
                $builder = Reports::pedigreeDataReport(\Yii::$app->request->post());
                $query = null;
                break;
            default:
                $builder = null;
                $query = null;
        }
        //print_r($query);

        if($builder){
            $success_msg = Lang::t('Report Queued Successfully. You will be notified once your report is ready for download');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $report = new AdhocReport();
                $report->name = $name;
                $report->raw_sql = $builder->rawQuery();
                $report->status = AdhocReport::STATUS_QUEUED;
                $report->options = json_encode([
                    'filterConditions' => $builder->filterConditions,
                    'filterValues' => $builder->filterValues,
                    'excludeFromReport' => $builder->excludeFromReport,
                    'extraFilterExpressions' => $builder->extraFilterExpressions,
                    'extraSelectExpressions' => $builder->extraSelectExpressions,
                    'fieldAliases' => $builder->fieldAliases,
                    'decodeFields' => $builder->decodeFields,
                    'fieldAliasMapping' => $builder->fieldAliasMapping,
                    'limit' => $builder->limit,
                    'orderby' => $builder->orderBy,
                    'country_id' => $builder->country_id,
                    'reportModel' => $builder->model,
                ]);
                if($report->save()){
                    $transaction->commit();
                    ReportGenerator::push(['queueId' => $report->id]);
                    $redirect = Url::to(['/reports/adhoc-report/index']);
                    return Json::encode(['success' => true, 'message' => $success_msg, 'redirectUrl' => $redirect, 'forceRedirect' => false]);
                }
                else{
                    Yii::debug($report->getErrors());
                    return Json::encode(['success' => false, 'message' => $report->getErrors()]);
                }
            }
            catch (\Exception $e) {
                $transaction->rollBack();
                Yii::debug($e->getTrace());
                return Json::encode(['success' => false, 'message' => $e->getMessage()]);
            }

        }
        return Json::encode(['success' => false, 'message' => 'Report not generated']);

    }
}
