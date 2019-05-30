<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-05-23
 * Time: 11:25 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\County;
use yii\helpers\Json;

class CountyController extends MasterDataController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'County';
    }

    public function actionIndex()
    {
        $searchModel = County::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new County(['is_active' => 1, 'country' => SystemSettings::getDefaultCountry()]);
        return $model->simpleAjaxSave();
    }

    public function actionUpdate($id)
    {
        $model = County::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        return County::softDelete($id);
    }

    public function actionGetList($country_id)
    {
        $data = County::getListData('name', 'name', false, ['country' => $country_id]);
        return Json::encode($data);
    }
}