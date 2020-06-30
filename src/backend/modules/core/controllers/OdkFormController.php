<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-04
 * Time: 2:44 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\core\Constants;
use backend\modules\core\models\OdkForm;
use common\helpers\Lang;
use common\helpers\Url;
use console\jobs\ODKFormProcessor;
use Yii;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;

class OdkFormController extends Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_ODK_JSON;
        $this->resourceLabel = 'ODK JSON File';
    }


    public function actionIndex($tab = 1, $country_id = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $searchModel = OdkForm::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
        ]);
        switch ($tab) {
            case 2:
                $searchModel->is_processed = 1;
                break;
            case 3:
                $searchModel->is_processed = 1;
                $searchModel->has_errors = 1;
                break;
            case 4:
                $searchModel->is_processed = 1;
                $searchModel->has_errors = 0;
                break;
            default:
                $searchModel->is_processed = 0;
        }
        $searchModel->country_id = $country_id;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $model = $this->loadModel($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);
        $model = new OdkForm([]);
        $model->setScenario(OdkForm::SCENARIO_UPLOAD);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                $transaction->commit();

                Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));

                return $this->redirect(Url::getReturnUrl(['index']));
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->hasPrivilege(Acl::ACTION_DELETE);
        $model = $this->loadModel($id);
        return OdkForm::softDelete($model->id);
    }

    /**
     * @param $id
     * @return OdkForm
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    protected function loadModel($id)
    {
        if (is_string($id) && !is_numeric($id)) {
            $model = OdkForm::loadModel(['uuid' => $id]);
        } else {
            $model = OdkForm::loadModel($id);
        }

        return $model;
    }

    public function actionProcess($id)
    {
        if (!Yii::$app->request->isPost) {
            throw new ForbiddenHttpException();
        }
        $model = OdkForm::loadModel($id);
        if ( ODKFormProcessor::push(['itemId' => $model->id])) {
            return Lang::t("ODK JSON Form id: {$model->id} successfully requeued");
        } else {
            Yii::debug($model->getErrors());
            return Lang::t("ODK JSON Form id: {$model->id} failed to requeue");
        }
    }
}