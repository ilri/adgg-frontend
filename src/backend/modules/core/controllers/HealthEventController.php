<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-01-14
 * Time: 10:44 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadHealthEvent;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\HealthEvent;
use common\controllers\UploadExcelTrait;
use Yii;

class HealthEventController extends Controller
{
    use AnimalEventTrait, UploadExcelTrait;

    public function init()
    {
        parent::init();

        $this->resource = Constants::RES_ANIMAL_EVENTS;
        $this->resourceLabel = 'Health';
    }

    public function actionIndex($animal_id = null, $country_id = null, $org_id = null, $client_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $from = null, $to = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        return $this->renderIndexAction(AnimalEvent::EVENT_TYPE_HEALTH, $animal_id, $country_id, $org_id, $client_id, $region_id, $district_id, $ward_id, $village_id, $from, $to);
    }

    public function actionUpload()
    {
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadHealthEvent(HealthEvent::class);
        $resp = $this->uploadExcelConsole($form, 'health-event/index', Yii::$app->request->queryParams);
        if ($resp !== false) {
            return $resp;
        }

        return $this->render('@coreModule/views/animal-event/upload', [
            'model' => $form,
        ]);
    }

    public function actionUploadPreview()
    {
        $form = new UploadHealthEvent(HealthEvent::class);
        return $form->previewAction();
    }
}