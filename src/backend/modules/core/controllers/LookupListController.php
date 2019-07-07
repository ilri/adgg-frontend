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

    public function actionIndex($list_type_id = null)
    {
        $searchModel = LookupList::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'with' => ['listType'],
        ]);
        $searchModel->is_active = 1;
        $searchModel->list_type_id = $list_type_id;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate($list_type_id = null)
    {
        $model = new LookupList(['is_active' => 1, 'list_type_id' => $list_type_id]);
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