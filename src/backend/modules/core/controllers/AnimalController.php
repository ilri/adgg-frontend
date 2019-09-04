<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-08
 * Time: 2:32 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\auth\Session;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadAnimals;
use backend\modules\core\models\Animal;
use common\helpers\Lang;
use common\helpers\Url;
use Yii;
use yii\db\Exception;

class AnimalController extends Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_ANIMAL;
        $this->resourceLabel = 'Animal';
    }

    public function actionIndex($type = null, $org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $name = null, $tag_id = null)
    {
        if (Session::isOrganization()) {
            $org_id = Session::getOrgId();
        }
        if (Session::isRegionUser()) {
            $region_id = Session::getRegionId();
        } elseif (Session::isDistrictUser()) {
            $district_id = Session::getDistrictId();
        } elseif (Session::isWardUser()) {
            $ward_id = Session::getWardId();
        } elseif (Session::isVillageUser()) {
            $village_id = Session::getVillageId();
        }
        if (empty($type)) {
            $type = Animal::TYPE_COW;
        }
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $searchModel = Animal::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['farm', 'region', 'district', 'ward', 'village', 'sire', 'dam'],
        ]);
        $searchModel->type = $type;
        $searchModel->org_id = $org_id;
        $searchModel->region_id = $region_id;
        $searchModel->district_id = $district_id;
        $searchModel->ward_id = $ward_id;
        $searchModel->village_id = $village_id;
        $searchModel->name = $name;
        $searchModel->tag_id = $tag_id;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }


    public function actionView($id)
    {
        $model = Animal::loadModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate($farm_id = null, $animal_type = null)
    {
        $model = new Animal(['farm_id' => $farm_id, 'animal_type' => $animal_type]);
        if ($this->handlePostedData($model)) {
            Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));
            return $this->redirect(Url::getReturnUrl(['index', 'id' => $model->id]));
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if ($this->handlePostedData($model)) {
            Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));
            return $this->redirect(Url::getReturnUrl(['index', 'id' => $model->id]));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function handlePostedData(Animal &$model)
    {
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                $transaction->commit();
                return true;
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }
        }
        return false;
    }

    public function actionUpload($type = null)
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);
        if (empty($type)) {
            $type = Animal::TYPE_COW;
        }

        $form = new UploadAnimals(Animal::class, ['type' => $type]);
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate() && $form->addToExcelQueue()) {
                Yii::$app->session->setFlash('success', Lang::t('File queued for processing. You will get notification once the file processing is completed.'));
                return json_encode(['success' => true, 'redirectUrl' => Url::to(['index', 'type' => $type])]);
            } else {
                return json_encode(['success' => false, 'message' => $form->getErrors()]);
            }
        }

        return $this->render('upload', [
            'model' => $form,
        ]);
    }

    public function actionUploadPreview()
    {
        $form = new UploadAnimals(Animal::class);
        return $form->previewAction();
    }

    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        return Animal::softDelete($model->id);
    }


    /**
     * @param $id
     * @return Animal
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    protected function loadModel($id)
    {
        if (is_string($id) && !is_numeric($id)) {
            $model = Animal::loadModel(['uuid' => $id]);
        } else {
            $model = Animal::loadModel($id);
        }

        return $model;
    }
}