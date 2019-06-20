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
use backend\modules\core\forms\UploadClients;
use backend\modules\core\models\Client;
use common\helpers\Lang;
use Yii;
use yii\db\Exception;
use yii\helpers\Url;

class ClientController extends Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_CLIENT;
        $this->resourceLabel = 'Client';
    }

    public function actionIndex($org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $name = null, $code = null, $phone = null, $is_head = null, $project = null, $farm_type = null, $gender_code = null, $is_active = null)
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
        list($condition, $params) = Client::appendOrgSessionIdCondition($condition, $params);
        $searchModel = Client::searchModel([
            'defaultOrder' => ['name' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['org', 'region', 'district', 'ward', 'village'],
        ]);
        $searchModel->org_id = $org_id;
        $searchModel->region_id = $region_id;
        $searchModel->district_id = $district_id;
        $searchModel->ward_id = $ward_id;
        $searchModel->village_id = $village_id;
        $searchModel->name = $name;
        $searchModel->code = $code;
        $searchModel->phone = $phone;
        $searchModel->is_head = $is_head;
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
        $model = new Client(['org_id' => $org_id, 'is_active' => 1]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                $transaction->commit();

                Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));

                return $this->redirect(\common\helpers\Url::getReturnUrl(['index', 'id' => $model->id]));
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                $transaction->commit();

                Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));

                return $this->redirect(\common\helpers\Url::getReturnUrl(['index', 'id' => $model->id]));
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpload()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadClients([]);
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate() && $form->addToExcelQueue()) {
                //process the file
                $form->saveExcelData();
                if (count($form->getSavedRows()) > 0) {
                    $successMsg = Lang::t('{n} rows successfully uploaded.', ['n' => count($form->getSavedRows())]);
                    Yii::$app->session->setFlash('success', $successMsg);
                }
                if (count($form->getFailedRows()) > 0) {
                    $warningMsg = '<p>' . Lang::t('{n} rows could could not be saved.', ['n' => count($form->getFailedRows())]) . '</p>';
                    $warningMsg .= '<ul style="max-height: 200px;overflow: auto">';
                    foreach ($form->getFailedRows() as $n => $message) {
                        $warningMsg .= '<li>' . $message . '</li>';
                    }
                    $warningMsg .= '</ul>';
                    Yii::$app->session->setFlash('warning', $warningMsg);
                }
                return json_encode(['success' => true, 'savedRows' => $form->getSavedRows(), 'failedRows' => $form->getFailedRows(), 'redirectUrl' => Url::to(['index'])]);
            } else {
                return json_encode(['success' => false, 'message' => $form->getErrors()]);
            }
        }

        return $this->render('upload', [
            'model' => $form,
        ]);
    }

    public function actionUploadPreview()
    {
        $form = new UploadClients();
        return $form->previewAction();
    }

    /**
     * @param $id
     * @return Client
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    protected function loadModel($id)
    {
        if (is_string($id) && !is_numeric($id)) {
            $model = Client::loadModel(['uuid' => $id]);
        } else {
            $model = Client::loadModel($id);
        }

        return $model;
    }

}