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
use backend\modules\core\models\Country;
use backend\modules\core\models\Farm;
use common\controllers\UploadExcelTrait;
use common\helpers\Lang;
use common\helpers\Url;
use Yii;
use yii\db\Exception;
use function foo\func;

class AnimalController extends Controller
{
    use UploadExcelTrait;

    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_ANIMAL;
        $this->resourceLabel = 'Animal';
    }

    public function actionIndex($country_id = null, $org_id = null, $client_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $animal_type = null, $farm_id = null, $farm_type = null, $main_breed = null, $name = null, $tag_id = null, $sire_tag_id = null, $dam_tag_id = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $country_id = Session::getCountryId($country_id);
        $org_id = Session::getOrgId($org_id);
        $client_id = Session::getClientId($client_id);
        $region_id = Session::getRegionId($region_id);
        $district_id = Session::getDistrictId($district_id);
        $ward_id = Session::getWardId($ward_id);
        $village_id = Session::getVillageId($village_id);
        $country = Country::findOne(['id' => $country_id]);
        $condition = '';
        $params = [];
        $searchModel = Animal::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
            'joinWith' => ['farm' => function (yii\db\ActiveQuery $query) use ($farm_type) {
                if (Session::isVillageUser()) {
                    $query->andFilterWhere([Farm::tableName() . '.farm_type' => $farm_type, Farm::tableName() . '.field_agent_id' => Session::getUserId()]);
                } else {
                    $query->andFilterWhere([Farm::tableName() . '.farm_type' => $farm_type]);
                }
            }],
            'with' => ['country', 'org', 'client', 'farm', 'region', 'district', 'ward', 'village', 'sire', 'dam'],
        ]);
        $searchModel->country_id = $country_id;
        $searchModel->org_id = $org_id;
        $searchModel->client_id = $client_id;
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
            'country' => $country,
        ]);
    }


    public function actionView($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $model = Animal::loadModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate($farm_id = null, $animal_type = null)
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);
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
        $this->hasPrivilege(Acl::ACTION_UPDATE);
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

    public function actionUpload()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadAnimals(Animal::class);
        $resp = $this->uploadExcelConsole($form, 'index', Yii::$app->request->queryParams);
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
        $this->hasPrivilege(Acl::ACTION_DELETE);
        return Animal::softDelete($id);
    }
}