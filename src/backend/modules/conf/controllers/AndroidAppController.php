<?php

namespace backend\modules\conf\controllers;

use backend\modules\auth\Acl;
use backend\modules\auth\Session;
use backend\modules\conf\models\AndroidApps;
use common\helpers\FileManager;
use common\helpers\Lang;
use common\helpers\Str;
use yii\web\ForbiddenHttpException;

class AndroidAppController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!Session::isDev()) {
            throw new ForbiddenHttpException(Lang::t('403_error'));
        }
        $this->resourceLabel = 'Android App Version';
        parent::init();
    }


    public function actionIndex()
    {
        $searchModel = AndroidApps::searchModel([
            'defaultOrder' => ['version_code' => SORT_DESC]
        ]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @return bool|string
     * @throws \yii\base\ExitException
     */
    public function actionCreate()
    {
        $model = new AndroidApps(['is_active' => 1]);
        return $model->simpleAjaxSave();
    }

    /**
     * @param $id
     * @return bool|string
     * @throws \yii\base\ExitException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = AndroidApps::loadModel($id);
        return $model->simpleAjaxSave();
    }

    /**
     * @param $id
     * @return bool
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        return AndroidApps::softDelete($id);
    }

    /**
     * @param $id
     * @throws ForbiddenHttpException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDownload($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);

        $model = AndroidApps::loadModel($id);
        FileManager::downloadFile($model->getAPKPath(), Str::removeWhitespace($model->apk_file), 'application/vnd.android.package-archive');
    }
}