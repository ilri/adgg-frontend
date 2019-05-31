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
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Organization;
use common\helpers\DateUtils;
use common\helpers\Lang;
use Yii;
use yii\db\Exception;
use common\helpers\Url;
use yii\web\BadRequestHttpException;

class CountryUnitController extends Controller
{
    /**
     * @var int
     */
    public $activeTab;

    public function init()
    {
        parent::init();
    }

    protected function initAction($type = null)
    {
        if ($type == CountryUnits::TYPE_REGION) {
            $this->resourceLabel = 'Region';
            $this->resource = Constants::RES_REGION;
        } elseif ($type == CountryUnits::TYPE_DISTRICT) {
            $this->resourceLabel = 'District';
            $this->resource = Constants::RES_DISTRICT;
        } elseif ($type == CountryUnits::TYPE_WARD) {
            $this->resourceLabel = 'Ward';
            $this->resource = Constants::RES_WARD;
        } elseif ($type == CountryUnits::TYPE_VILLAGE) {
            $this->resourceLabel = 'Village';
            $this->resource = Constants::RES_VILLAGE;
        }

        // check permissions, for all default existing actions
        $this->checkDefaultActionsPermissions($this->action);
        //set default page titles
        $this->setDefaultPageTitles($this->action);
    }


    public function actionIndex($type)
    {
        $this->initAction($type);
        $condition = '';
        $params = [];

        $searchModel = CountryUnits::searchModel([
            'defaultOrder' => ['name' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
        ]);
        $searchModel->type = $type;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }



    public function actionCreate($type)
    {
        $this->initAction($type);
        $model = new Organization([
            'status' => Organization::STATUS_PENDING_APPROVAL,
            'is_approved' => 0,
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
            $model = Organization::loadModel(['uuid' => $id]);
        } else
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