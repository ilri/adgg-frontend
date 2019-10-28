<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-28
 * Time: 10:43 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\models\AnimalEvent;
use common\helpers\DateUtils;

trait AnimalEventTrait
{
    use SessionTrait;

    protected function renderIndexAction($event_type, $animal_id = null, $org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $from = null, $to = null)
    {
        $dateFilter = DateUtils::getDateFilterParams($from, $to, 'event_date', false, false);
        $condition = $dateFilter['condition'];
        $params = [];
        $searchModel = AnimalEvent::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['animal'],
        ]);
        $searchModel->animal_id = $animal_id;
        $searchModel->event_type = $event_type;
        $searchModel->_dateFilterFrom = $dateFilter['from'];
        $searchModel->_dateFilterTo = $dateFilter['to'];
        $searchModel = $this->setSessionData($searchModel, $org_id, $region_id, $district_id, $ward_id, $village_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }
}