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
        $post = \Yii::$app->request->post();
        print_r($post);
        exit;
    }
}
