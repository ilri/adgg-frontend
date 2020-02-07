<?php


namespace backend\modules\core\controllers;


use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Organization;

class EventListController extends Controller
{

    public function actionIndex($org_id = null)
    {
        $events = AnimalEvent::eventTypeOptions();
        $country = Organization::findOne(['id' => $org_id]);
        return $this->render('/animal-event/event-lists', [
            'country' => $country,
            'events' => $events,
            'org_id' => $org_id,
        ]);
    }
}