<?php

namespace api\modules\v1\controllers;

use backend\modules\auth\Session;
use backend\modules\conf\models\AndroidApps;
use yii\web\ForbiddenHttpException;

class AndroidAppVersionController extends \yii\rest\ActiveController
{
    public function init()
    {
        $this->modelClass = AndroidApps::class;
        parent::init();
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $versions = AndroidApps::getData(['version_code', 'version_name', 'apk_file', 'app_url'], ['is_active' => 1], [], ['orderBy' => ['version_code' => SORT_DESC], 'limit' => 1]);
        if (empty($versions)) {
            return [];
        }
        if (Session::isPrivilegedAdmin() || Session::isCountryUser() || Session::isOrganizationUser()) {
            return $versions[0];
        } else {
            throw new ForbiddenHttpException("Not allowed to access this page");

        }
    }
}