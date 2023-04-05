<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-28
 * Time: 10:43 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Session;
use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Country;
use common\helpers\DateUtils;
use Yii;

trait AnimalEventTrait
{
    use SessionTrait;

    protected function renderIndexAction($event_type = null, $animal_id = null, $country_id = null, $org_id = null, $client_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $from = null, $to = null)
    {
        $dateFilter = DateUtils::getDateFilterParams($from, $to, 'event_date', false, false);
        $condition = $dateFilter['condition'];
        $params = [];
        $country = Country::findOne(['id' => $country_id]);
        $searchModel = AnimalEvent::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['animal', 'fieldAgent'],
        ]);
        $animalTagId = Yii::$app->request->get('animal_tag_id', null);
        if (!empty($animalTagId)) {
            $animal_id = Animal::getScalar('id', ['tag_id' => $animalTagId]);
        }
        $searchModel->animal_id = $animal_id;
        if (Session::isVillageUser()) {
            $searchModel->field_agent_id = Session::getUserId();
        }
        $searchModel->event_type = $event_type;
        $searchModel->_dateFilterFrom = $dateFilter['from'];
        $searchModel->_dateFilterTo = $dateFilter['to'];
        $searchModel = $this->setSessionData($searchModel, $country_id, $org_id, $client_id, $region_id, $district_id, $ward_id, $village_id);

        $grid = null;
        switch ($event_type) {
            case AnimalEvent::EVENT_TYPE_CALVING:
                $grid = 'calving';
                break;
            case AnimalEvent::EVENT_TYPE_MILKING:
                $grid = 'milking';
                break;
            case AnimalEvent::EVENT_TYPE_AI:
                $grid = 'ai';
                break;
            case AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS:
                $grid = 'pregnancy_diagnosis';
                break;
            case AnimalEvent::EVENT_TYPE_SYNCHRONIZATION:
                $grid = 'synchronization';
                break;
            case AnimalEvent::EVENT_TYPE_WEIGHTS:
                $grid = 'weights';
                break;
            case AnimalEvent::EVENT_TYPE_FEEDING:
                $grid = 'feeding';
                break;
            case AnimalEvent::EVENT_TYPE_PARASITE_INFECTION:
                $grid = 'parasite_infection';
                break;
            case AnimalEvent::EVENT_TYPE_INJURY:
                $grid = 'injury';
                break;
            case AnimalEvent::EVENT_TYPE_HOOF_HEALTH:
                $grid = 'hoof_health';
                break;
            case AnimalEvent::EVENT_TYPE_VACCINATION:
                $grid = 'vaccination';
                break;
            case AnimalEvent::EVENT_TYPE_HEALTH:
                $grid = 'health';
                break;
            case AnimalEvent::EVENT_TYPE_HOOF_TREATMENT:
                $grid = 'hoof_treatment';
                break;
            case AnimalEvent::EVENT_TYPE_EXITS:
                $grid = 'exits';
                break;
            case AnimalEvent::EVENT_TYPE_SAMPLING:
                $grid = 'sampling';
                break;
            case AnimalEvent::EVENT_TYPE_STRAW:
                $grid = 'straw';
                break;
        }

        return $this->render('@coreModule/views/animal-event/index', [
            'searchModel' => $searchModel,
            'grid' => $grid,
            'country' => $country,
        ]);
    }
}