<?php

namespace backend\modules\auth\controllers;

use backend\modules\auth\Constants;
use backend\modules\auth\forms\UploadUsers;
use backend\modules\auth\models\UserLevels;
use backend\modules\auth\Session;
use backend\modules\core\models\Organization;
use common\controllers\UploadExcelTrait;
use common\helpers\DateUtils;
use Yii;
use backend\modules\auth\Acl;
use backend\modules\auth\models\Users;
use common\helpers\Lang;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use app\modules\auth\models\PasswordResetHistory;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UserController extends Controller
{
    use UploadExcelTrait;
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->resourceLabel = 'User';
        $this->resource = Constants::RES_USER;
        $this->activeMenu = Constants::MENU_USER_MANAGEMENT;
    }


    public function actionIndex($level_id = null, $org_id = null, $name = null, $username = null, $email = null, $phone = null, $role_id = null, $status = Users::STATUS_ACTIVE, $from = null, $to = null)
    {
        $orgModel = null;
        if (Session::isOrganization()) {
            $org_id = Session::getOrgId();
            $level_id = UserLevels::LEVEL_COUNTRY;
        }
        if (!empty($org_id)) {
            $orgModel = Organization::loadModel($org_id);
        }
        $date_filter = DateUtils::getDateFilterParams($from, $to, 'last_login', false, true);
        $condition = $date_filter['condition'];
        $params = [];

        list($condition, $params) = Users::appendLevelCondition($condition, $params);
        $searchModel = Users::searchModel([
            'defaultOrder' => ['username' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['level', 'role', 'org'],
        ]);
        $searchModel->org_id = $org_id;
        $searchModel->level_id = $level_id;
        $searchModel->status = Users::STATUS_ACTIVE;
        $searchModel->name = $name;
        $searchModel->username = $username;
        $searchModel->email = $email;
        $searchModel->phone = $phone;
        $searchModel->role_id = $role_id;
        $searchModel->status = $status;
        $searchModel->_dateFilterFrom = $date_filter['from'];
        $searchModel->_dateFilterTo = $date_filter['to'];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'orgModel' => $orgModel,
        ]);
    }

    public function actionView($id)
    {
        $model = Users::loadModel($id);
        $model->checkPermission(true, true, true, true);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate($level_id = null, $org_id = null)
    {
        if (Session::isOrganization()) {
            $org_id = Session::getOrgId();
            $level_id = UserLevels::LEVEL_COUNTRY;
        }

        $model = new Users([
            'level_id' => $level_id,
            'org_id' => $org_id,
            'status' => Users::STATUS_ACTIVE,
            'scenario' => Users::SCENARIO_CREATE,
            'send_email' => true,
            'require_password_change' => 1,
        ]);
        $validateAttributes = null;
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
        }
        if ($model->auto_generate_password) {
            $validateAttributes = ['role_id', 'name', 'username', 'phone', 'email', 'level_id'];
        }
        $model->ajaxValidate($validateAttributes);
        if (Yii::$app->request->isPost && $model->validate($validateAttributes) && $model->save(false)) {
            Yii::$app->session->setFlash(self::FLASH_SUCCESS, Lang::t('SUCCESS_MESSAGE'));
            return $this->redirect(['index', 'level_id' => $model->level_id, 'org_id' => $model->org_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = Users::loadModel($id);
        $model->setScenario(Users::SCENARIO_UPDATE);
        $model->checkPermission(true);
        $model->ajaxValidate();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash(self::FLASH_SUCCESS, Lang::t('SUCCESS_MESSAGE'));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $model = Users::loadModel($id);
        $model->checkPermission(true, false, false, false);
        Users::softDelete($id);
    }

    public function actionChangePassword()
    {
        $model = Users::loadModel(Yii::$app->user->id);
        $model->setScenario(Users::SCENARIO_CHANGE_PASSWORD);
        $model->require_password_change = 0;

        $passwordResetHistoryModel = new PasswordResetHistory();
        $passwordResetHistoryModel->loadOldData($model);
        $model->ajaxValidate();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $passwordResetHistoryModel->logPasswordReset($model);
            Yii::$app->session->setFlash(self::FLASH_SUCCESS, Lang::t('Password changed successfully.'));
            return $this->refresh();
        }

        return $this->render('changePassword', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($id)
    {
        $this->hasPrivilege(Acl::ACTION_UPDATE);

        $model = Users::loadModel($id);
        $model->send_email = true;
        $model->auto_generate_password = false;
        $model->setScenario(Users::SCENARIO_RESET_PASSWORD);
        $model->checkPermission(true, false, false, false);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
        }
        $validateAttributes = null;
        if ($model->auto_generate_password) {
            $validateAttributes = ['auto_generate_password'];
        }
        $model->ajaxValidate($validateAttributes);
        $passwordResetHistoryModel = new PasswordResetHistory();
        $passwordResetHistoryModel->loadOldData($model);
        if (Yii::$app->request->isPost && $model->validate($validateAttributes) && $model->save(false)) {
            $passwordResetHistoryModel->logPasswordReset($model);
            $model->sendNewLoginDetailsEmail();
            Yii::$app->session->setFlash(self::FLASH_SUCCESS, Lang::t('SUCCESS_MESSAGE'));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionChangeStatus($id, $status)
    {
        $this->hasPrivilege(Acl::ACTION_UPDATE);

        $model = Users::loadModel($id);
        $model->checkPermission(true, false, false, false);
        $valid_status = array_keys(Users::statusOptions());
        if (!in_array($status, $valid_status)) {
            throw new BadRequestHttpException();
        }
        $model->status = $status;
        $response = ['success' => false, 'redirectUrl' => Url::to(['view', 'id' => $model->id])];
        if ($model->save(false)) {
            Yii::$app->session->setFlash(self::FLASH_SUCCESS, Lang::t('Success.'));
            $response['success'] = true;
        }

        Yii::$app->session->setFlash(self::FLASH_SUCCESS, Lang::t('Success.'));
        return json_encode($response);
    }

    public function actionGetList($org_id = null)
    {
        if (empty($org_id))
            $org_id = null;
        $data = Users::getListData('id', 'name', false, ['org_id' => $org_id]);
        return json_encode($data);
    }

    /**
     * @param $level_id
     * @param null $org_id
     * @return bool|false|string
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpload($level_id, $org_id = null)
    {
        if (Session::isOrganization()) {
            $org_id = Session::getOrgId();
        }
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadUsers(Users::class, ['org_id' => $org_id, 'level_id' => $level_id]);
        /*
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
                return json_encode(['success' => true, 'savedRows' => $form->getSavedRows(), 'failedRows' => $form->getFailedRows(), 'redirectUrl' => Url::to(['index', 'org_id' => $org_id, 'level_id' => $level_id])]);
            } else {
                return json_encode(['success' => false, 'message' => $form->getErrors()]);
            }
        }
        */
        $resp = $this->uploadExcelConsole($form, 'index', []);
        if ($resp !== false) {
            return $resp;
        }

        return $this->render('upload', [
            'model' => $form,
        ]);
    }

    /**
     * @param $level_id
     * @param null $org_id
     * @return bool|string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUploadPreview($level_id, $org_id = null)
    {
        $form = new UploadUsers(Users::class, ['level_id' => $level_id, 'org_id' => $org_id]);
        return $form->previewAction();
    }
}
