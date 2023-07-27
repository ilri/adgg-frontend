<?php

namespace backend\modules\reports\controllers;

use backend\modules\auth\Acl;
use backend\modules\auth\Session;
use backend\modules\reports\Constants;
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

    public function actionIndex($country_id = null)
    {
        $country_id = Session::getCountryId($country_id);
        return $this->render('index',[
            'country_id' => $country_id,
        ]);
    }

    public function actionView($type, $country_id = null){
        $country_id = Session::getCountryId($country_id);
        $from = null; $to = null;
        $date_filter = DateUtils::getDateFilterParams($from, $to, 'created_at', false, true);

        $tpl_type = null;
        switch ($type){
            case Constants::REPORT_TYPE_PEDIGREE:
                $tpl_type = 'pedigree';
                break;
            case Constants::REPORT_TYPE_MILKDATA:
                $tpl_type = 'milkdata';
                break;
            case Constants::REPORT_TYPE_PEDIGREE_FILE:
            case Constants::REPORT_TYPE_PEDIGREE_FILE2:
                $tpl_type = 'pedigreefile';
                break;
            case Constants::REPORT_TYPE_TESTDAY_MILKDATA:
                $tpl_type = 'testdaymilkdata';
                break;
            case Constants::REPORT_TYPE_TESTDAY_MILKDATA2:
                $tpl_type = 'testdaymilkdata2';
                break;
            case Constants::REPORT_TYPE_CALFDATA:
                $tpl_type = 'calfdata';
                break;
        }
        $searchModel = AdhocReport::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
        ]);
        $searchModel->is_standard = 1;
        $searchModel->type = $type;
        $searchModel->country_id = $country_id;

        return $this->render('view', [
            'type' => $type,
            'tpl' => $tpl_type,
            'country_id' => $country_id,
            'searchModel' => $searchModel,
            'filterOptions' => [
                'country_id' => null,
                'region_id' => null,
                'district_id' => null,
                'ward_id' => null,
                'village_id' => null,
                'dateFilterFrom' => $date_filter['from'],
                'dateFilterTo' => $date_filter['to'],
            ],
        ]);
    }

    public function actionGenerate(int $type){
        $name = '';
        $builder = null;
        $returnUrl = Url::to(['/reports/adhoc-report/index']);
        $country_id = null;
        $extraOptions = [];
        if ($type){
            $country_id = \Yii::$app->request->post('country_id');
            $returnUrl = Url::to(['/reports/default/view', 'type' => $type, 'country_id' => $country_id]);
            switch ($type){
                case Constants::REPORT_TYPE_MILKDATA:
                    $builder = Reports::milkDataReport(\Yii::$app->request->post());
                    $name = $builder->name;
                    $query = null;
                    break;
                case Constants::REPORT_TYPE_PEDIGREE:
                    $builder = Reports::pedigreeDataReport(\Yii::$app->request->post());
                    $name = $builder->name;
                    $query = null;
                    break;
                case Constants::REPORT_TYPE_PEDIGREE_FILE:
                    $builder = Reports::pedigreeFileDataReport(\Yii::$app->request->post());
                    $name = $builder->name;
                    $query = null;
                    break;
                case Constants::REPORT_TYPE_PEDIGREE_FILE2:
                    $builder = Reports::pedigreeFileDataReport(\Yii::$app->request->post(), 2);
                    $name = $builder->name;
                    $extraOptions = [
                        'version' => 2,
                    ];
                    $query = null;
                    break;
                case Constants::REPORT_TYPE_TESTDAY_MILKDATA:
                    $builder = Reports::testDayMilkDataReport(\Yii::$app->request->post());
                    $name = $builder->name;
                    $query = null;
                    break;
                case Constants::REPORT_TYPE_TESTDAY_MILKDATA2:
                    $builder = Reports::testDayMilkDataReport(\Yii::$app->request->post(), 2);
                    $name = $builder->name;
                    $extraOptions = [
                        'version' => 2,
                    ];
                    $query = null;
                    break;
                case Constants::REPORT_TYPE_CALFDATA:
                    $builder = Reports::calfDataReport(\Yii::$app->request->post());
                    $name = $builder->name;
                    $query = null;
                    break;
                case Constants::REPORT_TYPE_HAIR_SAMPLING:
                    $builder = Reports::hairsampleReport(\Yii::$app->request->post());
                    $name = $builder->name;
                    $query = null;
                    break;
                default:
                    $builder = null;
                    $query = null;
            }
        }

        if ($builder) {
            $success_msg = Lang::t('Report Queued Successfully. You will be notified once your report is ready for download');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $report = new AdhocReport();
                $report->name = $name;
                $report->type = $type;
                $report->is_standard = 1;
                $report->country_id = $country_id;
                $report->raw_sql = $builder->rawQuery();
                $report->status = AdhocReport::STATUS_QUEUED;
                $report->options = json_encode(array_merge([
                    'filterConditions' => $builder->filterConditions,
                    'filterValues' => $builder->filterValues,
                    'excludeFromReport' => $builder->excludeFromReport,
                    'extraFilterExpressions' => $builder->extraFilterExpressions,
                    'extraSelectExpressions' => $builder->extraSelectExpressions,
                    'fieldAliases' => $builder->fieldAliases,
                    'decodeFields' => $builder->decodeFields,
                    'fieldAliasMapping' => $builder->fieldAliasMapping,
                    'rowTransformer' => $builder->rowTransformer,
                    'limit' => $builder->limit,
                    'orderby' => $builder->orderBy,
                    'country_id' => $builder->country_id,
                    'reportModel' => $builder->model,
                ], $extraOptions));
                if($report->save()){
                    $transaction->commit();
                    ReportGenerator::push(['queueId' => $report->id]);
                    $redirect = $returnUrl;
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
