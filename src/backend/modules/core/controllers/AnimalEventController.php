<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-28
 * Time: 10:41 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadFarms;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Farm;
use common\controllers\UploadExcelTrait;

class AnimalEventController extends Controller
{
    use AnimalEventTrait, UploadExcelTrait;

    public function init()
    {
        parent::init();

        $this->resource = Constants::RES_ANIMAL_EVENTS;
        $this->resourceLabel = 'Animal Event';
    }

    public function actionIndex($event_type = null, $animal_id = null, $org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $from = null, $to = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        return $this->renderIndexAction($event_type, $animal_id, $org_id, $region_id, $district_id, $ward_id, $village_id, $from, $to);
    }

    public function actionView($id)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $model = AnimalEvent::loadModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }
}