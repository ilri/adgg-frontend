<?php


namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Organization;

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
        $searchModel = Organization::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'pageSize' => SystemSettings::getPaginationSize(),
            'enablePagination' => true,
        ]);
        return $searchModel->search();
    }

    /**
     * @return array
     */
    public function actionLanding()
    {
        $data = [];
        $countries = Organization::find()->orderBy(['id' => SORT_ASC])->all();
        foreach ($countries as $country) {
            $data[] = [
                'Country Dashboard' => [
                    'country_id' => $country->id,
                    'Name' => $country->name,
                    'No Of Farms' => CountriesDashboardStats::getLandingPageStats($country->id, CountriesDashboardStats::FARM),
                    'No of Animals ' => CountriesDashboardStats::getLandingPageStats($country->id, CountriesDashboardStats::ANIMAL),
                ]
            ];
        }
        return $data;
    }

    /**
     * @param integer $report_id
     * @param integer $org_id
     * @return array
     */
    public function actionCountryReport($report_id, $org_id)
    {
        return CountriesDashboardStats::getCountryReports($report_id, $org_id);
    }
}