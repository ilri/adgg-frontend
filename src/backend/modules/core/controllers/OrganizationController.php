<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-05-23
 * Time: 2:46 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\Constants;
use backend\modules\core\models\Organization;
use common\helpers\DateUtils;
use common\helpers\Lang;
use Yii;
use yii\db\Exception;
use common\helpers\Url;
use yii\web\BadRequestHttpException;

class OrganizationController extends Controller
{
    /**
     * @var int
     */
    public $activeTab;

    public function init()
    {
        parent::init();
    }

    protected function initAction($businessType = null, $isMember = null, $is_supplier = null)
    {
        if ($isMember) {
            $this->activeTab = Yii::$app->request->get('tab') ?? Constants::TAB_ALL_MEMBERS;
            $this->resourceLabel = 'Member';
            $this->resource = Constants::RES_MEMBERS;
            if ($businessType == Organization::BUSINESS_TYPE_PHARMACY) {
                $this->resourceLabel = 'Pharmacy';
            } elseif ($businessType == Organization::BUSINESS_TYPE_HOSPITAL) {
                $this->resourceLabel = 'Hospital';
            } elseif ($businessType == Organization::BUSINESS_TYPE_CLINIC) {
                $this->resourceLabel = 'Clinic';
            }
        } elseif ($is_supplier) {
            $this->activeTab = Yii::$app->request->get('tab') ?? Constants::TAB_ALL_SUPPLIERS;
            $this->resourceLabel = 'Supplier';
            $this->resource = Constants::RES_SUPPLIER;
            if ($businessType == Organization::BUSINESS_TYPE_MANUFACTURER) {
                $this->resourceLabel = 'Manufacturer';
            } elseif ($businessType == Organization::BUSINESS_TYPE_DISTRIBUTOR) {
                $this->resourceLabel = 'Distributor';
            }
        }
        // check permissions, for all default existing actions
        $this->checkDefaultActionsPermissions($this->action);
        //set default page titles
        $this->setDefaultPageTitles($this->action);
    }


    public function actionIndex($name = null, $account_no = null, $business_type = null, $status = Organization::STATUS_ACTIVE, $is_approved = null, $is_supplier = null, $is_member = null, $account_manager_id = null, $is_credit_requested = null)
    {
        $this->initAction($business_type, $is_member, $is_supplier);
        $condition = '';
        $params = [];

        $searchModel = Organization::searchModel([
            'defaultOrder' => ['name' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['accountManager'],
        ]);
        $searchModel->account_no = $account_no;
        $searchModel->name = $name;
        $searchModel->business_type = $business_type;
        $searchModel->status = $status;
        $searchModel->is_approved = $is_approved;
        $searchModel->is_credit_requested = $is_credit_requested;
        $searchModel->is_supplier = $is_supplier;
        $searchModel->is_member = $is_member;
        $searchModel->account_manager_id = $account_manager_id;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        $model = Organization::loadModel($id);
        $this->initAction($model->business_type, $model->is_member, $model->is_supplier);
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate($is_member = null, $is_supplier = null, $business_type = null)
    {
        $this->initAction($business_type, $is_member, $is_supplier);
        $model = new Organization([
            'status' => Organization::STATUS_PENDING_APPROVAL,
            'is_approved' => 0,
            'is_member' => $is_member,
            'is_supplier' => $is_supplier,
            'business_type' => $business_type,
            'country' => SystemSettings::getDefaultCountry(),
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                $transaction->commit();

                Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));

                return $this->redirect(Url::getReturnUrl(['view', 'id' => $model->id]));
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
        if (is_string($id) && !is_numeric($id)) {
            $model = Organization::loadModel(['uuid'=>$id]);
        }else
        $model = Organization::loadModel($id);
        $this->initAction($model->business_type, $model->is_member, $model->is_supplier);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                $transaction->commit();

                Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));

                return $this->redirect(Url::getReturnUrl(['view', 'id' => $model->id]));
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionApprove($id)
    {
        $this->hasPrivilege(Acl::ACTION_UPDATE);
        $model = Organization::loadModel($id);
        $model->date_approved = DateUtils::getToday();
        $model->setScenario(Organization::SCENARIO_APPROVE);

        return $model->simpleAjaxSave('forms/_approve', 'view', ['id' => $id]);
    }

    public function actionDelete($id)
    {
        return Organization::softDelete($id);
    }

    public function actionChangeStatus($id, $status)
    {
        $model = Organization::loadModel($id);
        $this->initAction($model->business_type, $model->is_member, $model->is_supplier);
        $this->hasPrivilege(Acl::ACTION_UPDATE);

        $valid_status = array_keys(Organization::statusOptions());
        if (!in_array($status, $valid_status)) {
            throw new BadRequestHttpException();
        }
        $model->status = $status;
        $response = ['success' => false, 'redirectUrl' => Url::to(['view', 'id' => $model->id])];
        if ($model->save(false)) {
            Yii::$app->session->setFlash(self::FLASH_SUCCESS, Lang::t('Success.'));
            $response['success'] = true;
        }

        return json_encode($response);
    }

}