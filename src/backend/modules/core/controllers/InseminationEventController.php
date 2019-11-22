<?php


namespace backend\modules\core\controllers;

use backend\modules\core\Constants;
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

    public function actionIndex($animal_id = null, $org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $from = null, $to = null)
    {
        return $this->renderIndexAction(AnimalEvent::EVENT_TYPE_AI, $animal_id, $org_id, $region_id, $district_id, $ward_id, $village_id, $from, $to);
    }
}