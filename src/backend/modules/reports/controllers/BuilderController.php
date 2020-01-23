<?php

namespace backend\modules\reports\controllers;

use backend\modules\reports\models\AdhocReport;
use backend\modules\reports\models\ReportBuilder;
use common\helpers\Lang;
use common\helpers\Url;
use common\models\ActiveRecord;
use console\jobs\ReportGenerator;
use Yii;
use yii\helpers\Json;

/**
 * Default controller for the `reports` module
 */
class BuilderController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        $models = ReportBuilder::reportableModels();

        return $this->render('index',[
            'models' => $models,
        ]);
    }

    protected function build(){
        $req =   \Yii::$app->request;
        //$post = \Yii::$app->request->post();
        $modelName = $req->post('model');
        $filterConditions = $req->post('filterCondition', []); // array
        $filterValues = $req->post('filterValue', []); // array
        $limit = $req->post('limit', 100);
        $orderBy = $req->post('orderby', '');
        $org_id = $req->post('org_id', '');
        $name = $req->post('name', time());

        $builder = new ReportBuilder();
        $builder->model = $modelName;
        $builder->filterConditions = $filterConditions;
        $builder->filterValues = $filterValues;
        $builder->orderBy = $orderBy;
        $builder->limit = $limit;
        $builder->org_id = $org_id;
        $builder->name = $name;

        return $builder;
    }

    protected function generateQuery(){
        return $this->build()->rawQuery();
    }

    public function actionGenerateQuery()
    {
        echo $this->generateQuery();
        exit;
    }

    public function actionSaveReport(){
        $success_msg = Lang::t('SUCCESS_MESSAGE');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $builder = $this->build();
            // save name, raw_query
            $report = new AdhocReport();
            $report->name = $builder->name;
            $report->raw_sql = $builder->rawQuery();
            $report->status = AdhocReport::STATUS_QUEUED;
            if($report->save()){
                ReportGenerator::push(['queueId' => $report->id]);
                return Json::encode(['success' => true, 'message' => $success_msg, 'redirectUrl' => '', 'forceRedirect' => false]);
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
