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
use common\controllers\UploadExcelTrait;
use common\helpers\Lang;
use common\helpers\Url;
use Yii;
use yii\db\Exception;

class AnimalController extends Controller
{
    use UploadExcelTrait;

    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_ANIMAL;
        $this->resourceLabel = 'Animal';
    }

    public function actionIndex($org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $animal_type = null, $farm_id = null, $main_breed = null, $name = null, $tag_id = null, $sire_tag_id = null, $dam_tag_id = null)
    {
        $org_id = Session::getOrgId($org_id);
        $region_id = Session::getRegionId($region_id);
        $district_id = Session::getDistrictId($district_id);
        $ward_id = Session::getWardId($ward_id);
        $village_id = Session::getVillageId($village_id);
        $condition = '';
        $params = [];
        $searchModel = Animal::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['farm', 'region', 'district', 'ward', 'village', 'sire', 'dam'],
        ]);
        $searchModel->org_id = $org_id;
        $searchModel->region_id = $region_id;
        $searchModel->district_id = $district_id;
        $searchModel->ward_id = $ward_id;
        $searchModel->village_id = $village_id;
        $searchModel->name = $name;
        $searchModel->tag_id = $tag_id;
        $searchModel->animal_type = $animal_type;
        $searchModel->farm_id = $farm_id;
        $searchModel->sire_tag_id = $sire_tag_id;
        $searchModel->dam_tag_id = $dam_tag_id;
        $searchModel->main_breed=$main_breed;

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
            return $this->redirect(Url::getReturnUrl(['view', 'id' => $model->id]));
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Animal::loadModel($id);
        if ($this->handlePostedData($model)) {
            Yii::$app->session->setFlash('success', Lang::t('SUCCESS_MESSAGE'));
            return $this->redirect(Url::getReturnUrl(['view', 'id' => $model->id]));
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

        $form = new UploadAnimals(Animal::class);
        $resp = $this->uploadExcelConsole($form, 'index', []);
        if ($resp !== false) {
            return $resp;
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
        return Animal::softDelete($id);
    }
}