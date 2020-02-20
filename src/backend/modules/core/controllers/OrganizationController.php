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
use backend\modules\core\models\Organization;

class OrganizationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Organization';
        $this->resource = Constants::RES_ORGANIZATION;
    }

    public function actionIndex($country_id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $countryModel = Country::loadModel(['id' => $country_id]);
        $searchModel = Organization::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;
        $searchModel->country_id = $countryModel->id;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'countryModel' => $countryModel,
        ]);
    }

    public function actionCreate($country_id = null)
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);
        $model = new Organization(['is_active' => 1, 'country_id' => $country_id]);
        return $model->simpleAjaxSave();
    }

    public function actionView($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $model = Organization::loadModel($id);

        return $this->renderAjax('view', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->hasPrivilege(Acl::ACTION_UPDATE);
        $model = Organization::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        $this->hasPrivilege(Acl::ACTION_DELETE);
        return Organization::softDelete($id);
    }

    public function actionGetList($country_id = null, $placeholder = false)
    {
        $data = Organization::getListData('id', 'name', $placeholder, ['country_id' => $country_id]);
        return json_encode($data);
    }
}