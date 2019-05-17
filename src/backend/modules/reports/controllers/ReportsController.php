<?php
/**
 * Created by PhpStorm.
 * @author Fred <mconyango@gmail.com>
 * Date: 2018-09-26
 * Time: 23:12
 */

namespace backend\modules\reports\controllers;


use backend\modules\reports\Constants;
use backend\modules\reports\models\Reports;

class ReportsController extends Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_REPORTS_SETTINGS;
        $this->activeMenu = \backend\modules\conf\Constants::MENU_SETTINGS;
        $this->resourceLabel = 'Report';
    }

    public function actionIndex()
    {
        $searchModel = Reports::searchModel(['defaultOrder' => ['display_order' => SORT_ASC]]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Reports([
            'is_active' => true,
            'display_order' => Reports::getNextDisplayOrder(),
        ]);

        return $model->simpleAjaxSave();
    }

    public function actionUpdate($id)
    {
        $model = Reports::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        Reports::softDelete($id);
    }

}