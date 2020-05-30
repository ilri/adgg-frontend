<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 4:17 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\core\models\TableAttribute;
use backend\modules\core\models\TableAttributesGroup;

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

    protected function setResourceLabel($table_id)
    {
        $this->resourceLabel = TableAttribute::decodeTableId($table_id);
        $this->pageTitle = null;
        $this->setDefaultPageTitles($this->action);
    }

    public function actionIndex($table_id = TableAttribute::TABLE_FARM)
    {
        $this->resourceLabel = 'Extendable Table';
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $this->setResourceLabel($table_id);
        $searchModel = TableAttribute::searchModel([
            'defaultOrder' => ['group_id' => SORT_ASC, 'id' => SORT_ASC],
            'with' => ['group', 'listType','farmMetadataType'],
        ]);
        $searchModel->is_active = 1;
        $searchModel->table_id = $table_id;

        $groupSearchModel = TableAttributesGroup::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        $groupSearchModel->is_active = 1;
        $groupSearchModel->table_id = $table_id;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'groupSearchModel' => $groupSearchModel,
        ]);
    }


    public function actionCreate($table_id, $event_type = null)
    {
        $model = new TableAttribute(['is_active' => 1, 'table_id' => $table_id, 'event_type' => $event_type]);
        return $model->simpleAjaxSave('_form', 'index', ['table_id' => $model->table_id]);
    }

    public function actionUpdate($id)
    {
        $model = TableAttribute::loadModel($id);
        return $model->simpleAjaxSave('_form', 'index', ['table_id' => $model->table_id]);
    }
}