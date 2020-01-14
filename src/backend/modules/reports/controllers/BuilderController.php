<?php

namespace backend\modules\reports\controllers;

use backend\modules\reports\models\ReportBuilder;
use common\models\ActiveRecord;

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

    public function  actionGenerateQuery()
    {
        $req =   \Yii::$app->request;
        //$post = \Yii::$app->request->post();
        $modelName = $req->post('model');
        $filterConditions = $req->post('filterCondition', []); // array
        $filterValues = $req->post('filterValue', []); // array
        $limit = $req->post('limit', 100);
        $orderBy = $req->post('orderby', '');
        $org_id = $req->post('org_id', '');

        $builder = new ReportBuilder();
        $builder->model = $modelName;
        $builder->filterConditions = $filterConditions;
        $builder->filterValues = $filterValues;
        $builder->orderBy = $orderBy;
        $builder->limit = $limit;
        $builder->org_id = $org_id;
        echo $builder->rawQuery();
        //print_r($builder->generateQuery());

        exit;
    }
}
