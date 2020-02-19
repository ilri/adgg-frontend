<?php


namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\OrganizationRef;

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
        $searchModel = OrganizationRef::searchModel([
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
        $countries = OrganizationRef::find()->orderBy(['id' => SORT_ASC])->all();
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
     * @param $report_id
     * @param $country_id
     * @return array
     * @throws \Exception
     */
    public function actionCountryReport($report_id, $country_id)
    {
        return CountriesDashboardStats::getCountryReports($report_id, $country_id);
    }
}