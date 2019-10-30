<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 6:43 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\auth\Session;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadFarms;
use backend\modules\core\models\Farm;
use common\controllers\UploadExcelTrait;
use common\helpers\Lang;
use common\helpers\Url;
use Yii;
use yii\db\Exception;

class FarmController extends Controller
{
    use UploadExcelTrait;

    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_FARM;
        $this->resourceLabel = 'Farm';
    }

    public function actionIndex($org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $name = null, $code = null, $phone = null, $project = null, $farm_type = null, $gender_code = null, $is_active = null)
    {
        if (Session::isOrganization()) {
            $org_id = Session::getOrgId();
        }
        if (Session::isRegionUser()) {
            $region_id = Session::getRegionId();
        } elseif (Session::isDistrictUser()) {
            $district_id = Session::getDistrictId();
        } elseif (Session::isWardUser()) {
            $ward_id = Session::getWardId();
        } elseif (Session::isVillageUser()) {
            $village_id = Session::getVillageId();
        }
        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $searchModel = Farm::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['org', 'region', 'district', 'ward', 'village','attributeValues','fieldAgent'],
        ]);
        $searchModel->org_id = $org_id;
        $searchModel->region_id = $region_id;
        $searchModel->district_id = $district_id;
        $searchModel->ward_id = $ward_id;
        $searchModel->village_id = $village_id;
        $searchModel->name = $name;
        $searchModel->code = $code;
        $searchModel->phone = $phone;
        $searchModel->project = $project;
        $searchModel->farm_type = $farm_type;
        $searchModel->gender_code = $gender_code;
        $searchModel->is_active = $is_active;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate($org_id = null)
    {
        $model = new Farm(['org_id' => $org_id, 'is_active' => 1]);
        if ($this->handlePostedData($model)) {
            Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));
            return $this->redirect(Url::getReturnUrl(['index', 'id' => $model->id]));
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if ($this->handlePostedData($model)) {
            Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));
            return $this->redirect(Url::getReturnUrl(['index', 'id' => $model->id]));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function handlePostedData(Farm &$model)
    {
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                $transaction->commit();
                return true;
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }
        }
        return false;
    }

    public function actionUpload()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadFarms(Farm::class);
        $resp = $this->uploadExcelConsole($form, 'index', []);
        if ($resp !== false) {
            return $resp;
        }

        return $this->render('upload', [
            'model' => $form,
        ]);
    }

    public function actionUploadPreview()
    {
        $form = new UploadFarms(Farm::class);
        return $form->previewAction();
    }

    public function actionGetList($village_id = null)
    {
        $data = Farm::getListData('id', 'name', true, ['village_id' => $village_id]);
        return json_encode($data);
    }

    /**
     * @param $id
     * @return Farm
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    protected function loadModel($id)
    {
        if (is_string($id) && !is_numeric($id)) {
            $model = Farm::loadModel(['uuid' => $id]);
        } else {
            $model = Farm::loadModel($id);
        }

        return $model;
    }

}