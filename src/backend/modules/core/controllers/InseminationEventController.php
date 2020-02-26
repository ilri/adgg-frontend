<?php


namespace backend\modules\core\controllers;

use backend\modules\auth\Acl;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadAIEvent;
use backend\modules\core\models\AIEvent;
use backend\modules\core\models\AnimalEvent;
use common\controllers\UploadExcelTrait;

class InseminationEventController extends Controller
{
    use AnimalEventTrait, UploadExcelTrait;

    public function init()
    {
        parent::init();

        $this->resource = Constants::RES_ANIMAL_EVENTS;
        $this->resourceLabel = 'Insemination';
    }

    public function actionIndex($animal_id = null, $country_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $from = null, $to = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        return $this->renderIndexAction(AnimalEvent::EVENT_TYPE_AI, $animal_id, $country_id, $region_id, $district_id, $ward_id, $village_id, $from, $to);
    }

    public function actionUpload()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadAIEvent( AIEvent::class);
        $resp = $this->uploadExcelConsole($form, 'insemination-event/index', []);
        if ($resp !== false) {
            return $resp;
        }

        return $this->render('@coreModule/views/animal-event/upload', [
            'model' => $form,
        ]);
    }

    public function actionUploadPreview()
    {
        $form = new UploadAIEvent(AIEvent::class);
        return $form->previewAction();
    }
}