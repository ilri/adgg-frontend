<?php

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\auth\Session;
use backend\modules\core\Constants;
use backend\modules\core\models\FarmMetadataType;
use yii\web\ForbiddenHttpException;

class FarmMetadataTypeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Farm Metadata Type';
        $this->resource = Constants::RES_FARM;
    }

    public function beforeAction($action)
    {
        $this->enableDefaultAcl = false;
        if (parent::beforeAction($action)) {
            if (!Session::isDev()) {
                throw new ForbiddenHttpException();
            }
            return true;
        }
        return false;
    }


    public function actionIndex()
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $searchModel = FarmMetadataType::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;
        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);
        $model = new FarmMetadataType([
            'is_active' => 1,
            'code' => FarmMetadataType::getScalar('max([[code]])+1') ?? 1,
        ]);
        return $model->simpleAjaxSave();
    }

    public function actionView($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $model = FarmMetadataType::loadModel($id);

        return $this->renderAjax('view', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->hasPrivilege(Acl::ACTION_UPDATE);
        $model = FarmMetadataType::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        $this->hasPrivilege(Acl::ACTION_DELETE);
        return FarmMetadataType::softDelete($id);
    }
}