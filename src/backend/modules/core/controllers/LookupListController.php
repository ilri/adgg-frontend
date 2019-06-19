<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 1:26 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\models\LookupList;

class LookupListController extends MasterDataController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Lookup List';
    }

    public function actionIndex()
    {
        $searchModel = LookupList::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'with' => ['listType'],
        ]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new LookupList(['is_active' => 1]);
        return $model->simpleAjaxSave();
    }

    public function actionUpdate($id)
    {
        $model = LookupList::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        return LookupList::softDelete($id);
    }
}