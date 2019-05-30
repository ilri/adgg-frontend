<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2015/12/07
 * Time: 3:01 AM
 */

namespace backend\modules\conf\controllers;

use backend\modules\conf\models\JobProcesses;
use backend\modules\conf\models\Jobs;

class JobManagerController extends DevController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Job';
    }

    public function actionIndex()
    {
        $searchModel = Jobs::searchModel(['defaultOrder' => ['id' => SORT_ASC]]);
        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Jobs(['is_active' => 1]);
        return $model->simpleAjaxSave();
    }

    public function actionUpdate($id)
    {
        $model = Jobs::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        Jobs::softDelete($id);
    }

    public function actionStart($id)
    {
        Jobs::startJob($id);
    }

    public function actionStop($id)
    {
        Jobs::stopJob($id);
    }
}