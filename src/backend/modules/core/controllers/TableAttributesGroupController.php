<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 3:02 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\models\TableAttributesGroup;

class TableAttributesGroupController extends MasterDataController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Attributes Group';
    }


    public function actionCreate($table_id)
    {
        $model = new TableAttributesGroup(['is_active' => 1, 'table_id' => $table_id]);
        return $model->simpleAjaxSave('_form', 'extendable-table/index', ['table_id' => $model->table_id]);
    }

    public function actionUpdate($id)
    {
        $model = TableAttributesGroup::loadModel($id);
        return $model->simpleAjaxSave('_form', 'extendable-table/index', ['table_id' => $model->table_id]);
    }

    public function actionDelete($id)
    {
        return TableAttributesGroup::softDelete($id);
    }
}