<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 2:33 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\models\ExtendableTable;
use backend\modules\core\models\TableAttribute;
use backend\modules\core\models\TableAttributesGroup;

class ExtendableTableController extends MasterDataController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Extendable Table';
    }

    protected function setResourceLabel($table_id)
    {
        $this->resourceLabel = ExtendableTable::decodeTableId($table_id);
        $this->pageTitle = null;
        $this->setDefaultPageTitles($this->action);
    }

    public function actionIndex($table_id = ExtendableTable::TABLE_CLIENT)
    {
        $this->setResourceLabel($table_id);
        $searchModel = TableAttribute::searchModel([
            'defaultOrder' => ['group_id' => SORT_ASC, 'id' => SORT_ASC],
            'with' => ['group', 'listType'],
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
}