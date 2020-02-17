<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-28
 * Time: 10:43 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use common\helpers\DateUtils;
use Yii;

trait AnimalEventTrait
{
    use SessionTrait;

    protected function renderIndexAction($event_type = null, $animal_id = null, $org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $from = null, $to = null)
    {
        $dateFilter = DateUtils::getDateFilterParams($from, $to, 'event_date', false, false);
        $condition = $dateFilter['condition'];
        $params = [];
        $searchModel = AnimalEvent::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['animal','fieldAgent'],
        ]);
        $animalTagId = Yii::$app->request->get('animal_tag_id', null);
        if (!empty($animalTagId)) {
            $animal_id = Animal::getScalar('id', ['tag_id' => $animalTagId]);
        }
        $searchModel->animal_id = $animal_id;
        $searchModel->event_type = $event_type;
        $searchModel->_dateFilterFrom = $dateFilter['from'];
        $searchModel->_dateFilterTo = $dateFilter['to'];
        $searchModel = $this->setSessionData($searchModel, $org_id, $region_id, $district_id, $ward_id, $village_id);

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
            case AnimalEvent::EVENT_TYPE_HEALTH:
                $grid = 'health';
                break;
            case AnimalEvent::EVENT_TYPE_FEEDING:
                $grid = 'feeding';
                break;
            case AnimalEvent::EVENT_TYPE_EXITS:
                $grid = 'exits';
                break;
        }

        return $this->render('@coreModule/views/animal-event/index', [
            'searchModel' => $searchModel,
            'grid' => $grid,
        ]);
    }
}