<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\AnimalEvent;
use common\helpers\DateUtils;

class AnimalEventController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = AnimalEvent::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex($event_type = null, $from = null, $to = null)
    {
        $dateFilter = DateUtils::getDateFilterParams($from, $to, 'event_date', false, false);
        $condition = $dateFilter['condition'];
        $params = [];
        $searchModel = AnimalEvent::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
            'enablePagination' => true,
            'pageSize' => SystemSettings::getPaginationSize(),
        ]);
        $searchModel->event_type = $event_type;
        return $searchModel->search();
    }
}