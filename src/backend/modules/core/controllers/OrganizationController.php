<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-05-23
 * Time: 2:46 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\Constants;
use backend\modules\core\models\Organization;
use common\helpers\Lang;
use Yii;
use yii\db\Exception;
use common\helpers\Url;

class OrganizationController extends Controller
{

    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_COUNTRY;
        $this->resourceLabel = 'Country';
    }


    public function actionIndex()
    {
        $searchModel = Organization::searchModel([
            'defaultOrder' => ['name' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        $model = Organization::loadModel($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new Organization([]);

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
        $model = Organization::loadModel($id);

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
}