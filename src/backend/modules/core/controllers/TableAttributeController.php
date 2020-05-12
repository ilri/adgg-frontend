<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 4:17 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\models\TableAttribute;

class TableAttributeController extends MasterDataController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->activeSubMenu = null;
        $this->resourceLabel = 'Attribute';
    }


    public function actionCreate($table_id, $event_type = null)
    {
        $model = new TableAttribute(['is_active' => 1, 'table_id' => $table_id, 'event_type' => $event_type]);
        return $model->simpleAjaxSave('_form', 'extendable-table/index', ['table_id' => $model->table_id]);
    }

    public function actionUpdate($id)
    {
        $model = TableAttribute::loadModel($id);
        return $model->simpleAjaxSave('_form', 'extendable-table/index', ['table_id' => $model->table_id]);
    }
}