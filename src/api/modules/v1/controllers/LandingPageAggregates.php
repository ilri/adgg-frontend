<?php


namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use backend\modules\core\models\CountriesDashboardStats;

class LandingPageAggregates extends ActiveController
{

    public function init()
    {
        parent::init();
    }

    public function actionIndex($param=null){
        return CountriesDashboardStats::getLandingPageAggregates($param);
    }
}