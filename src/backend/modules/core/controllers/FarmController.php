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
use backend\modules\core\forms\UploadFarmMetadata;
use backend\modules\core\forms\UploadFarms;
use backend\modules\core\models\Country;
use backend\modules\core\models\Farm;
use backend\modules\core\models\FarmMetadata;
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

    public function actionIndex($country_id = null, $org_id = null, $client_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $name = null, $code = null, $phone = null, $project = null, $farm_type = null, $gender_code = null, $is_active = null, $odk_code = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $country_id = Session::getCountryId($country_id);
        $org_id = Session::getOrgId($org_id);
        $client_id = Session::getClientId($client_id);
        $region_id = Session::getRegionId($region_id);
        $district_id = Session::getDistrictId($district_id);
        $ward_id = Session::getWardId($ward_id);
        $village_id = Session::getVillageId($village_id);
        $country = Country::findOne(['id' => $country_id]);
        $condition = '';
        $params = [];
        $searchModel = Farm::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['country', 'org', 'client', 'region', 'district', 'ward', 'village', 'fieldAgent'],
        ]);
        $searchModel->country_id = $country_id;
        $searchModel->org_id = $org_id;
        $searchModel->client_id = $client_id;
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
        $searchModel->odk_code = $odk_code;
        if (Session::isVillageUser()) {
            $searchModel->field_agent_id = Session::getUserId();
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'country' => $country,
        ]);
    }

    public function actionCreate($country_id = null)
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);
        $model = new Farm(['country_id' => $country_id, 'is_active' => 1]);
        if ($this->handlePostedData($model)) {
            Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));
            return $this->redirect(Url::getReturnUrl(['index', 'id' => $model->id]));
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $model = Farm::loadModel($id);

        return $this->render('view', [
            'farmModel' => $model,
        ]);
    }

    /**
     * @param $farm_id
     * @param $type
     * @return string
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionViewMetadata($farm_id, $type)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $metadataModel = FarmMetadata::find()->andWhere(['farm_id' => $farm_id, 'type' => $type])->one();
        $metadataModel->type = $type;
        $metadataModel->farm_id = $farm_id;
        $farmModel = Farm::loadModel($farm_id);

        return $this->render('view-metadata', [
            'metadataModel' => $metadataModel,
            'farmModel' => $farmModel,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->hasPrivilege(Acl::ACTION_UPDATE);
        $model = $this->loadModel($id);
        if ($this->handlePostedData($model)) {
            Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));
            return $this->redirect(Url::getReturnUrl(['view', 'id' => $model->id]));
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

    public function actionUpload($country_id = null)
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadFarms(Farm::class, ['country_id' => $country_id]);
        $resp = $this->uploadExcelConsole($form, 'index', Yii::$app->request->queryParams);
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

    public function actionUploadMetadata($country_id, $type)
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $className = FarmMetadata::getMetadataModelClassNameByType($type);
        $form = new UploadFarmMetadata($className, ['country_id' => $country_id]);
        $resp = $this->uploadExcelConsole($form, 'upload-metadata', ['country_id' => $country_id, 'type' => $type]);
        if ($resp !== false) {
            return $resp;
        }

        return $this->render('upload-metadata', [
            'model' => $form,
        ]);
    }

    public function actionUploadMetadataPreview($type)
    {
        $className = FarmMetadata::getMetadataModelClassNameByType($type);
        $form = new UploadFarmMetadata($className, []);
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