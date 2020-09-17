<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-09-17
 * Time: 6:12 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\models\AnimalBreedGroup;

class BreedGroupController extends MasterDataController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Breeds Group';
    }

    public function actionIndex()
    {
        $searchModel = AnimalBreedGroup::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new AnimalBreedGroup(['is_active' => 1]);
        return $model->simpleAjaxSave();
    }

    public function actionUpdate($id)
    {
        $model = AnimalBreedGroup::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        return AnimalBreedGroup::softDelete($id);
    }
}