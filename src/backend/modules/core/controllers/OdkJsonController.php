<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-04
 * Time: 2:44 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\Constants;
use backend\modules\core\models\OdkJsonQueue;
use common\helpers\Lang;
use common\helpers\Url;
use console\jobs\ProcessODKJson;
use Yii;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;

class OdkJsonController extends Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_ODK_JSON;
        $this->resourceLabel = 'ODK JSON File';
    }


    public function actionIndex($tab = 1)
    {
        $searchModel = OdkJsonQueue::searchModel([
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

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->loadModel($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new OdkJsonQueue([]);
        $model->setScenario(OdkJsonQueue::SCENARIO_UPLOAD);

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
        $model = $this->loadModel($id);
        return OdkJsonQueue::softDelete($model->id);
    }

    /**
     * @param $id
     * @return OdkJsonQueue
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    protected function loadModel($id)
    {
        if (is_string($id) && !is_numeric($id)) {
            $model = OdkJsonQueue::loadModel(['uuid' => $id]);
        } else {
            $model = OdkJsonQueue::loadModel($id);
        }

        return $model;
    }

    public function actionProcess($id)
    {
        if (!Yii::$app->request->isPost) {
            throw new ForbiddenHttpException();
        }

        $model = OdkJsonQueue::loadModel($id);
        ProcessODKJson::push(['queueId' => $model->id]);
    }
}