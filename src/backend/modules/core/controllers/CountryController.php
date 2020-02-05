<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-09-27 3:03 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\core\Constants;
use backend\modules\core\models\Country;

class CountryController extends MasterDataController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Country';
        $this->resource = Constants::RES_COUNTRY;
    }

    public function actionIndex()
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $searchModel = Country::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);
        $model = new Country(['is_active' => 1]);
        return $model->simpleAjaxSave();
    }

    public function actionUpdate($id)
    {
        $this->hasPrivilege(Acl::ACTION_UPDATE);
        $model = Country::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        $this->hasPrivilege(Acl::ACTION_DELETE);
        return Country::softDelete($id);
    }
}