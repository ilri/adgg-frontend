<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-05-23
 * Time: 1:03 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\conf\Constants;
use backend\modules\core\models\RegistrationDocumentType;

class RegistrationDocumentTypeController extends MasterDataController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_SETTINGS;
        $this->activeSubMenu = Constants::SUBMENU_REGISTRATION;
        $this->resourceLabel = 'Document Type';
    }

    public function actionIndex()
    {
        $searchModel = RegistrationDocumentType::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new RegistrationDocumentType(['is_active' => 1, 'has_start_date' => 1, 'has_renewal' => 1]);
        return $model->simpleAjaxSave();
    }

    public function actionUpdate($id)
    {
        $model = RegistrationDocumentType::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        return RegistrationDocumentType::softDelete($id);
    }
}