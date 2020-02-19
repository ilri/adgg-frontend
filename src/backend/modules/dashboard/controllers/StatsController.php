<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-04-19
 * Time: 12:04 PM
 */

namespace backend\modules\dashboard\controllers;


use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\MilkingReport;
use backend\modules\core\models\Country;

class StatsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'graphFilterOptions' => [
            ],
        ]);
    }

    public function actionDash($country_id = null)
    {
        $country = Country::findOne(['id' => $country_id]);
        return $this->render('dashboards', [
            'country_id' => $country_id,
            'country' => $country,
        ]);
    }

    public function actionFarmSummary($country_id = null)
    {
        $country = Country::findOne(['id' => $country_id]);
        return $this->render('farm-summary', [
            'country_id' => $country_id,
            'country' => $country,
        ]);
    }

    public function actionAnimalSummary($country_id = null)
    {
        $country = Country::findOne(['id' => $country_id]);
        return $this->render('animal-summary', [
            'country_id' => $country_id,
            'country' => $country
        ]);
    }

    public function actionDash1($country_id = null)
    {
        $country = Country::findOne(['id' => $country_id]);
        $dataProvider = MilkingReport::getLargeScaleFarmMilkDetails($country_id);
        return $this->render('lsf', [
            'dataProvider' => $dataProvider,
            'country_id' => $country_id,
            'country' => $country,
        ]);
    }

    public function actionDash2($country_id = null)
    {
        $dataProvider = CountriesDashboardStats::getGetAnimalsMilkingRecords($country_id);
        $country = Country::findOne(['id' => $country_id]);
        return $this->render('test-day', [
            'country_id' => $country_id,
            'dataProvider' => $dataProvider,
            'country' => $country,
        ]);
    }

    public function actionDash3($country_id = null)
    {
        $country = Country::findOne(['id' => $country_id]);
        return $this->render('genotyped-animals', [
            'country_id' => $country_id,
            'country' => $country,
        ]);
    }

    public function actionDash4($country_id = null)
    {
        $country = Country::findOne(['id' => $country_id]);
        return $this->render('pd-ai-cal', [
            'country_id' => $country_id,
            'country' => $country,
        ]);
    }

    public function actionGraph($graphType = null, $dateRange = null, $animal_type = null, $main_breed = null, $country_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null)
    {
        return $this->renderPartial('graph/_widget', [
            'graphType' => $graphType,
            'dateRange' => $dateRange,
            'graphFilterOptions' => [
                'animal_type' => $animal_type,
                'main_breed' => $main_breed,
                'country_id' => $country_id,
                'region_id' => $region_id,
                'district_id' => $district_id,
                'ward_id' => $ward_id,
                'village_id' => $village_id,
            ],
        ]);
    }
}