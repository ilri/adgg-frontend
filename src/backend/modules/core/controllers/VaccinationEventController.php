<?php


namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadVaccinationEvent;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\VaccinationEvent;
use common\controllers\UploadExcelTrait;

use Yii;

class VaccinationEventController extends Controller
{
    use AnimalEventTrait, UploadExcelTrait;

    public function init()
    {
        parent::init();

        $this->resource = Constants::RES_ANIMAL_EVENTS;
        $this->resourceLabel = 'Vaccination';
    }

    public function actionIndex($animal_id = null, $country_id = null, $org_id = null, $client_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $from = null, $to = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        return $this->renderIndexAction(AnimalEvent::EVENT_TYPE_VACCINATION, $animal_id, $country_id, $org_id, $client_id, $region_id, $district_id, $ward_id, $village_id, $from, $to);
    }

    public function actionUpload()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadVaccinationEvent(VaccinationEvent::class);
        $resp = $this->uploadExcelConsole($form, 'vaccination-event/index', Yii::$app->request->queryParams);
        if ($resp !== false) {
            return $resp;
        }

        return $this->render('@coreModule/views/animal-event/upload', [
            'model' => $form,
        ]);
    }

    public function actionUploadPreview()
    {
        $form = new UploadVaccinationEvent(VaccinationEvent::class);
        return $form->previewAction();
    }

}