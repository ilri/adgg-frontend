<?php


namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\core\Constants;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Country;

class EventListController extends Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_ANIMAL_EVENTS;
    }

    public function actionIndex($country_id = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $events = AnimalEvent::eventTypeOptions();
        $country = Country::findOne(['id' => $country_id]);
        return $this->render('/animal-event/event-lists', [
            'country' => $country,
            'events' => $events,
            'country_id' => $country_id,
        ]);
    }
}