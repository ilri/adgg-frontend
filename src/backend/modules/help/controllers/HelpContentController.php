<?php

namespace backend\modules\help\controllers;

use backend\modules\help\Constants;
use backend\modules\help\models\HelpContent;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * HelpContentController implements the CRUD actions for HelpContent model.
 */
class HelpContentController extends Controller
{
    public function init()
    {
        $this->resourceLabel = 'Help Content';
        $this->resource = Constants::RES_HELP;
        //$this->skipPermissionCheckOnAction = true;
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
        ];
    }


    public function actionIndex()
    {
        $searchModel = HelpContent::searchModel(['defaultOrder' => ['id' => SORT_ASC]]);
        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => HelpContent::loadModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new HelpContent();

        return $model->simpleSave('create', 'index');
    }

    public function actionUpdate($id)
    {
        $model = HelpContent::loadModel($id);

        return $model->simpleSave('update', 'index');
    }

    public function actionDelete($id)
    {
        HelpContent::softDelete($id);
    }
}
