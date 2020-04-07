<?php

namespace backend\modules\help\controllers;

use backend\modules\auth\Acl;
use backend\modules\help\Constants;
use backend\modules\help\models\HelpContent;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * HelpContentController implements the CRUD actions for HelpContent model.
 */
class HelpContentController extends Controller
{
    public function init()
    {
        $this->resourceLabel = 'Help Content';
        $this->resource = Constants::RES_HELP;
        //$this->skipPermissionCheckOnAction = true;
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
        ];
    }

    public function actionIndex()
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);

        $searchModel = HelpContent::searchModel(['defaultOrder' => ['id' => SORT_ASC]]);
        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionManual($forAndroid = false)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);

        $searchModel = HelpContent::searchModel(['defaultOrder' => ['id' => SORT_ASC]]);
        if ($forAndroid == true) {
            $this->resource = Constants::RES_ANDROID_APP_MANUAL;
            $searchModel->is_for_android = 1;
        } else {
            $searchModel->is_for_android = 0;
        }
        return $this->render('grid', [
            'searchModel' => $searchModel,
        ]);
    }
    public function actionView($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        return $this->render('view', [
            'model' => HelpContent::loadModel($id),
        ]);
    }

    public function actionRead($format = null, $module = null, $name = null, $forAndroid = false)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $models = HelpContent::find();
        $models->andFilterWhere(['LIKE', 'name', $name]);
        $models->andFilterWhere(['module_id' => $module]);
        if ($forAndroid == true) {
            $models->andFilterWhere(['is_for_android' => 1]);
        } else {
            $models->andFilterWhere(['is_for_android' => 0]);
        }
        if ($format !== null) {
            $content = $this->renderPartial('read', [
                'models' => $models->all(),
            ]);
            switch ($format) {
                case 'pdf':
                default:
                    $options = [];
                    return HelpContent::exportPdf($content, $options);
                    break;
            }
        }

        return $this->render('read', [
            'models' => $models->all(),
            'filterOptions' => [
                'module' => $module,
                'name' => $name,
            ]
        ]);
    }

    /**
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionCreate()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);
        $model = new HelpContent();

        return $model->simpleSave('create', 'index');
    }

    /**
     * @param $id
     * @return bool|string
     * @throws NotFoundHttpException
     * @throws \yii\base\ExitException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $this->hasPrivilege(Acl::ACTION_UPDATE);
        $model = HelpContent::loadModel($id);

        return $model->simpleSave('update', 'index');
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        $this->hasPrivilege(Acl::ACTION_DELETE);
        HelpContent::softDelete($id);
    }
}
