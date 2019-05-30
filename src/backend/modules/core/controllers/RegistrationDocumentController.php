<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-05-24
 * Time: 6:34 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\auth\Session;
use backend\modules\core\Constants;
use backend\modules\core\models\Organization;
use backend\modules\core\models\RegistrationDocument;
use common\helpers\DateUtils;
use common\helpers\FileManager;
use common\helpers\Str;

class RegistrationDocumentController extends Controller
{
    public function init()
    {
        parent::init();

        $this->resourceLabel = 'Registration Document';
        $this->resource = Constants::RES_REGISTRATION_DOCUMENT;
        $this->activeSubMenu = Constants::SUBMENU_REGISTRATION_DOCUMENTS;
    }

    public function actionIndex($org_id = null)
    {
        $orgModel = $this->getOrganizationModel($org_id);
        $searchModel = RegistrationDocument::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'with' => ['docType', 'approvedBy'],
        ]);
        $searchModel->is_active = 1;
        $searchModel->org_id = $orgModel->id;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'orgModel' => $orgModel,
        ]);
    }

    public function actionCreate($org_id)
    {
        $orgModel = $this->getOrganizationModel($org_id);
        $model = new RegistrationDocument([
            'org_id' => $orgModel->id,
            'is_active' => 1,
            'is_approved' => 0,
        ]);
        return $model->simpleAjaxSave('_form', 'organization/view', ['id' => $orgModel->id]);
    }

    public function actionUpdate($id)
    {
        $model = RegistrationDocument::loadModel($id);
        return $model->simpleAjaxSave('_form', 'organization/view', ['id' => $model->org_id]);
    }

    public function actionDelete($id)
    {
        RegistrationDocument::softDelete($id);
    }

    /**
     * @param string $uuid
     * @return Organization
     * @throws \yii\web\NotFoundHttpException
     */
    protected function getOrganizationModel($uuid)
    {
        if (Session::isOrganization()) {
            $orgModel = Organization::loadModel(['id' => Session::accountId()]);
        } else {
            $orgModel = Organization::loadModel(['uuid' => $uuid]);
        }
        return $orgModel;
    }

    public function actionDownload($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);

        $model = RegistrationDocument::loadModel(['uuid' => $id]);
        FileManager::downloadFile($model->getFilePath(), Str::removeWhitespace($model->file_name));
    }

    public function actionApprove($id)
    {
        $this->hasPrivilege(Acl::ACTION_UPDATE);
        $model = RegistrationDocument::loadModel($id);
        $model->date_approved = DateUtils::getToday();
        $model->setScenario(RegistrationDocument::SCENARIO_APPROVE);

        return $model->simpleAjaxSave('_approve', 'organization/view', ['id' => $model->org_id]);
    }

}