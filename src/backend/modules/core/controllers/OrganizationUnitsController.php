<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 1:58 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\auth\Session;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadOrganizationUnits;
use backend\modules\core\models\Organization;
use backend\modules\core\models\OrganizationUnits;
use common\controllers\UploadExcelTrait;
use yii\base\InvalidArgumentException;
use yii\helpers\Html;

class OrganizationUnitsController extends Controller
{
    use UploadExcelTrait;

    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_COUNTRY;
        $this->resourceLabel = 'Country';
    }

    public function setResourceLabel(Organization $country, $level)
    {
        switch ($level) {
            case OrganizationUnits::LEVEL_REGION:
                $this->resourceLabel = Html::encode($country->unit1_name);
                break;
            case OrganizationUnits::LEVEL_DISTRICT:
                $this->resourceLabel = Html::encode($country->unit2_name);
                break;
            case OrganizationUnits::LEVEL_WARD:
                $this->resourceLabel = Html::encode($country->unit3_name);
                break;
            case OrganizationUnits::LEVEL_VILLAGE:
                $this->resourceLabel = Html::encode($country->unit4_name);
                break;
            default:
                throw new InvalidArgumentException();
        }
        $this->pageTitle = null;
        $this->setDefaultPageTitles($this->action);
    }

    public function actionIndex($org_id, $level)
    {
        $orgModel = Organization::loadModel(['uuid' => $org_id]);
        $this->setResourceLabel($orgModel, $level);
        $searchModel = OrganizationUnits::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;
        $searchModel->org_id = $orgModel->id;
        $searchModel->level = $level;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'orgModel' => $orgModel,
        ]);
    }

    public function actionCreate($org_id, $level)
    {
        $orgModel = Organization::loadModel(['uuid' => $org_id]);
        $this->setResourceLabel($orgModel, $level);
        $model = new OrganizationUnits(['org_id' => $orgModel->id, 'level' => $level]);
        return $model->simpleAjaxSave('_form', 'organization/view', ['id' => $orgModel->uuid]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $this->setResourceLabel($model->org, $model->level);
        return $model->simpleAjaxSave('_form', 'organization/view', ['id' => $model->org->uuid]);
    }

    /**
     * @param $id
     * @return OrganizationUnits
     * @throws \yii\web\NotFoundHttpException
     */
    protected function loadModel($id)
    {
        if (is_string($id) && !is_numeric($id)) {
            $model = OrganizationUnits::loadModel(['uuid' => $id]);
        } else {
            $model = OrganizationUnits::loadModel($id);
        }

        return $model;
    }

    public function actionGetList($level, $org_id = null, $parent_id = null, $placeholder = false)
    {
        if ($level == OrganizationUnits::LEVEL_REGION) {
            $data = OrganizationUnits::getListData('id', 'name', $placeholder, ['org_id' => $org_id, 'level' => $level]);
        } else {
            $data = OrganizationUnits::getListData('id', 'name', $placeholder, ['parent_id' => $parent_id, 'level' => $level]);
        }
        return json_encode($data);
    }

    public function actionUpload($level, $org_id = null)
    {
        if (Session::isOrganization()) {
            $org_id = Session::getOrgId();
        }
        $orgModel = Organization::loadModel($org_id);
        $this->setResourceLabel($orgModel, $level);
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadOrganizationUnits(OrganizationUnits::class, ['org_id' => $orgModel->id, 'level' => $level]);
        $resp = $this->uploadExcelConsole($form, 'index', ['org_id' => $orgModel->uuid, 'level' => $level]);
        if ($resp !== false) {
            return $resp;
        }

        return $this->render('upload', [
            'model' => $form,
            'orgModel' => $orgModel,
        ]);
    }

    public function actionUploadPreview($level, $org_id)
    {
        $form = new UploadOrganizationUnits(OrganizationUnits::class, ['level' => $level, 'org_id' => $org_id]);
        return $form->previewAction();
    }
}