<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-14
 * Time: 2:44 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\auth\Session;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadHerds;
use backend\modules\core\models\AnimalHerd;
use common\controllers\UploadExcelTrait;
use common\helpers\Lang;
use common\helpers\Url;
use Yii;
use yii\db\Exception;

class HerdController extends Controller
{
    use SessionTrait, UploadExcelTrait;

    public function init()
    {
        parent::init();

        $this->resource = Constants::RES_HERD;
        $this->resourceLabel = 'Herd';
    }

    public function actionIndex($farm_id = null, $country_id = null, $org_id = null, $client_id = null, $name = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $country_id = Session::getCountryId($country_id);
        $org_id = Session::getOrgId($org_id);
        $client_id = Session::getClientId($client_id);
        $region_id = Session::getRegionId($region_id);
        $district_id = Session::getDistrictId($district_id);
        $ward_id = Session::getWardId($ward_id);
        $village_id = Session::getVillageId($village_id);
        $condition = '';
        $params = [];
        $searchModel = AnimalHerd::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['country', 'org', 'client', 'region', 'district', 'ward', 'village'],
        ]);
        $searchModel->country_id = $country_id;
        $searchModel->org_id = $org_id;
        $searchModel->client_id = $client_id;
        $searchModel->region_id = $region_id;
        $searchModel->district_id = $district_id;
        $searchModel->ward_id = $ward_id;
        $searchModel->village_id = $village_id;
        $searchModel->farm_id = $farm_id;
        $searchModel->name = $name;

        $searchModel = $this->setSessionData($searchModel, $country_id, $region_id, $district_id, $ward_id, $village_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'country_id' => $country_id,
        ]);
    }
    public function actionView($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $model = AnimalHerd::loadModel($id);

        return $this->render('view', [
            'model' => $model,
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
    protected function handlePostedData(AnimalHerd &$model)
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

        $form = new UploadHerds(AnimalHerd::class);
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
        $form = new UploadHerds(AnimalHerd::class);
        return $form->previewAction();
    }

    public function actionGetList($farm_id = null, $placeholder = false)
    {
        $data = AnimalHerd::getListData('id', 'name', $placeholder, ['farm_id' => $farm_id]);
        return json_encode($data);
    }
    /**
     * @param $id
     * @return AnimalHerd
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    protected function loadModel($id)
    {
        if (is_string($id) && !is_numeric($id)) {
            $model = AnimalHerd::loadModel(['uuid' => $id]);
        } else {
            $model = AnimalHerd::loadModel($id);
        }

        return $model;
    }

}