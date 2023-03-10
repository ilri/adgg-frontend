<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 1:11 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\models\ChoiceTypes;

class ListTypeController extends MasterDataController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'List Type';
    }

    public function actionIndex()
    {
        $searchModel = ChoiceTypes::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new ChoiceTypes(['is_active' => 1]);
        $model->id = (int)ChoiceTypes::getScalar('max([[id]])') + 1;
        return $model->simpleAjaxSave('_form', 'lookup-list/index', [], null, true, 'list_type_id');
    }

    public function actionUpdate($id)
    {
        $model = ChoiceTypes::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        return ChoiceTypes::softDelete($id);
    }
}