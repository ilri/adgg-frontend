<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-14
 * Time: 2:44 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadHerds;
use backend\modules\core\models\AnimalHerd;
use common\controllers\UploadExcelTrait;

class HerdController extends Controller
{
    use SessionTrait, UploadExcelTrait;

    public function init()
    {
        parent::init();

        $this->resource = Constants::RES_FARM;
        $this->resourceLabel = 'Herd';
    }

    public function actionIndex($farm_id = null, $org_id = null, $herd_id = null, $name = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null)
    {
        $condition = '';
        $params = [];
        $searchModel = AnimalHerd::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['org', 'region', 'district', 'ward', 'village'],
        ]);
        $searchModel->farm_id = $farm_id;
        $searchModel->herd_id = $herd_id;
        $searchModel->name = $name;

        $searchModel = $this->setSessionData($searchModel, $org_id, $region_id, $district_id, $ward_id, $village_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionUpload()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadHerds(AnimalHerd::class);
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
        $form = new UploadHerds(AnimalHerd::class);
        return $form->previewAction();
    }

}