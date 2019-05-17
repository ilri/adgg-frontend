<?php

namespace backend\modules\help\controllers;

use backend\modules\help\Constants;
use backend\modules\help\Help;
use backend\modules\help\models\HelpModules;
use yii\web\NotFoundHttpException;

/**
 * HelpModulesController implements the CRUD actions for HelpModules model.
 */
class HelpModulesController extends Controller
{

    public function init()
    {
        $this->resourceLabel = 'Help Modules';
        $this->resource = Constants::RES_HELP;
        //$this->skipPermissionCheckOnAction = true;
        parent::init();
    }

    /**
     * Lists all HelpModules models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = HelpModules::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            // skip the default one from the results
            'condition' => ['<>', 'slug', Help::DEFAULT_SLUG]
        ]);
        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single HelpModules model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new HelpModules model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HelpModules();

        return $model->simpleAjaxSave();
    }

    /**
     * Updates an existing HelpModules model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        return $model->simpleAjaxSave();
    }

    /**
     * Deletes an existing HelpModules model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        HelpModules::softDelete($id);
    }

    /**
     * Finds the HelpModules model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HelpModules the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        return HelpModules::loadModel($id);
    }
}
