<?php


namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use backend\modules\core\models\CountriesDashboardStats;

class LandingPageAggregatesController extends ActiveController
{
    public $modelClass = CountriesDashboardStats::class;

    public function init()
    {
        parent::init();
    }

    public function actionIndex($report_type=null){
        return CountriesDashboardStats::getLandingPageAggregates($report_type);
    }
}