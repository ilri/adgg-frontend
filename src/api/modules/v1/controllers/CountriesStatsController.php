<?php


namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\auth\Session;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;

class CountriesStatsController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = CountriesDashboardStats::class;

    public function init()
    {
        parent::init();
    }

    /**
     * @return \yii\data\ActiveDataProvider
     */
    public function actionCountriesList()
    {
        $user = \Yii::$app->user->identity;
        $searchModel = Country::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'pageSize' => SystemSettings::getPaginationSize(),
            'enablePagination' => true,
        ]);
        $searchModel->id = $user->country_id;
        return $searchModel->search();
    }
    /**
     * @param $report_id
     * @param $country_id
     * @return array
     * @throws \Exception
     */
    public function actionCountryReport($report_id, $country_id)
    {
        $user = \Yii::$app->user->identity;
        if ($user->country_id !== null){
            $country_id = $user->country_id;
        }
        return CountriesDashboardStats::getCountryReports($report_id, $country_id);
    }
}