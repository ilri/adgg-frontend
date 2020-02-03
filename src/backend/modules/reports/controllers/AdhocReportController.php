<?php

namespace backend\modules\reports\controllers;

use backend\modules\auth\Acl;
use backend\modules\auth\models\UserLevels;
use backend\modules\auth\Session;
use backend\modules\core\models\Organization;
use backend\modules\reports\Constants;
use backend\modules\reports\models\AdhocReport;
use common\helpers\DateUtils;
use common\helpers\FileManager;
use common\helpers\Lang;
use common\helpers\Str;
use common\models\ActiveRecord;
use console\jobs\ReportGenerator;
use Yii;
use yii\helpers\Json;

/**
 * Default controller for the `reports` module
 */
class AdhocReportController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->resourceLabel = 'Ad-hoc Reports';
        $this->resource = Constants::RES_REPORTS;
        $this->activeMenu = Constants::MENU_REPORTS;
    }

    public function actionIndex($name = null, $created_by = null, $status = null, $from = null, $to = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);

        $date_filter = DateUtils::getDateFilterParams($from, $to, 'created_at', false, false);
        $condition = $date_filter['condition'];
        $params = [];

        $searchModel = AdhocReport::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
        ]);
        $searchModel->name = $name;
        $searchModel->created_by = $created_by;
        $searchModel->status = $status ?? AdhocReport::STATUS_COMPLETED;
        $searchModel->_dateFilterFrom = $date_filter['from'];
        $searchModel->_dateFilterTo = $date_filter['to'];

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $model = AdhocReport::loadModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionDownloadFile($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);

        $model = AdhocReport::loadModel($id);
        FileManager::downloadFile($model->getFilePath(), Str::removeWhitespace($model->report_file));
    }

    public function actionRequeue($id){
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $model = AdhocReport::loadModel($id);
        $report = clone $model;
        $report->isNewRecord = true;
        $report->id = null;
        $report->report_file = null;
        $report->created_by = Yii::$app->user->id;
        $report->status = AdhocReport::STATUS_QUEUED;
        $success_msg = Lang::t('Report Queued Successfully. You will be notified once your report is ready for download');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if($report->save()){
                $transaction->commit();
                ReportGenerator::push(['queueId' => $report->id]);
                return Json::encode(['success' => true, 'message' => $success_msg, 'redirectUrl' => 'index', 'forceRedirect' => false]);
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

}
