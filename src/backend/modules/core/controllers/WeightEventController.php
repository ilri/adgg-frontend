<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-01-14
 * Time: 2:06 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadWeightEvent;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\WeightEvent;
use common\controllers\UploadExcelTrait;

class WeightEventController extends Controller
{
    use AnimalEventTrait, UploadExcelTrait;

    public function init()
    {
        parent::init();

        $this->resource = Constants::RES_ANIMAL_EVENTS;
        $this->resourceLabel = 'Weight';
    }

    public function actionIndex($animal_id = null, $org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $from = null, $to = null)
    {
        return $this->renderIndexAction(AnimalEvent::EVENT_TYPE_WEIGHTS, $animal_id, $org_id, $region_id, $district_id, $ward_id, $village_id, $from, $to);
    }

    public function actionUpload()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadWeightEvent(WeightEvent::class);
        $resp = $this->uploadExcelConsole($form, 'weight-event/index', []);
        if ($resp !== false) {
            return $resp;
        }

        return $this->render('@coreModule/views/animal-event/upload', [
            'model' => $form,
        ]);
    }

    public function actionUploadPreview()
    {
        $form = new UploadWeightEvent(WeightEvent::class);
        return $form->previewAction();
    }
}