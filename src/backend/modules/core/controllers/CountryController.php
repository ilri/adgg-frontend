<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-09-27 3:03 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\models\Country;

class CountryController extends MasterDataController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Country';
    }

    public function actionIndex()
    {
        $searchModel = Country::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Country(['is_active' => 1]);
        return $model->simpleAjaxSave();
    }

    public function actionUpdate($id)
    {
        $model = Country::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        return Country::softDelete($id);
    }
}