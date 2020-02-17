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
use backend\modules\core\models\Organization;
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

    /**
     * @param null $org_id
     * @return string
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionEventList($org_id = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $events = AnimalEvent::eventTypeOptions();
        $country = Organization::findOne(['id' => $org_id]);
        return $this->render('/animal-event/event-lists', [
            'country' => $country,
            'events' => $events,
            'org_id' => $org_id,
        ]);
    }

    /**
     * @param null $event_type
     * @param null $animal_id
     * @param null $org_id
     * @param null $region_id
     * @param null $district_id
     * @param null $ward_id
     * @param null $village_id
     * @param null $from
     * @param null $to
     * @return mixed
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
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