<?php
/**
 * Created by PhpStorm.
 * @author Fred <mconyango@gmail.com>
 * Date: 2018-05-28
 * Time: 20:22
 */

namespace backend\modules\core\controllers;


use backend\modules\core\models\Currency;

class CurrencyController extends MasterDataController
{
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Currency';
    }

    public function actionIndex()
    {
        $searchModel = Currency::searchModel([
            'defaultOrder' => ['iso3' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Currency(['is_active' => true]);
        return $model->simpleAjaxSave();
    }

    public function actionUpdate($id)
    {
        $model = Currency::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        return Currency::softDelete($id);
    }
}