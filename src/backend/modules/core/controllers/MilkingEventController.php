<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-15
 * Time: 8:20 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadMilkEvent;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\MilkingEvent;
use common\controllers\UploadExcelTrait;

class MilkingEventController extends Controller
{
    use UploadExcelTrait, AnimalEventTrait;

    public function init()
    {
        parent::init();

        $this->resource = Constants::RES_ANIMAL_EVENTS;
        $this->resourceLabel = 'Milking';
    }

    public function actionIndex($animal_id = null, $org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $from = null, $to = null)
    {
        return $this->renderIndexAction(AnimalEvent::EVENT_TYPE_MILKING, $animal_id, $org_id, $region_id, $district_id, $ward_id, $village_id, $from, $to);
    }

    public function actionUpload()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadMilkEvent(MilkingEvent::class);
        $resp = $this->uploadExcelConsole($form, 'milking-event/index', []);
        if ($resp !== false) {
            return $resp;
        }

        return $this->render('@coreModule/views/animal-event/upload', [
            'model' => $form,
        ]);
    }

    public function actionUploadPreview()
    {
        $form = new UploadMilkEvent(MilkingEvent::class);
        return $form->previewAction();
    }
}