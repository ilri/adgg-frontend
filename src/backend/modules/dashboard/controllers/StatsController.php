<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-04-19
 * Time: 12:04 PM
 */

namespace backend\modules\dashboard\controllers;


use backend\modules\core\models\Animal;
use backend\modules\core\models\LSFMilkingReport;
use backend\modules\core\models\Organization;

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

    public function actionDash($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
        return $this->render('dashboards', [
            'org_id' => $org_id,
            'country' => $country,
        ]);
    }

    public function actionFarmSummary($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
        return $this->render('farm-summary', [
            'org_id' => $org_id,
            'country' => $country,
        ]);
    }

    public function actionAnimalSummary($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
        return $this->render('animal-summary', [
            'org_id' => $org_id,
            'country' => $country
        ]);
    }

    public function actionDash1($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
        $dataProvider = LSFMilkingReport::getLargeScaleFarmMilkDetails($org_id);
        return $this->render('lsf', [
            'dataProvider' => $dataProvider,
            'org_id' => $org_id,
            'country' => $country,
        ]);
    }

    public function actionDash2($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
        return $this->render('test-day', [
            'org_id' => $org_id,
            'country' => $country,
        ]);
    }

    public function actionDash3($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
        return $this->render('genotyped-animals', [
            'org_id' => $org_id,
            'country' => $country,
        ]);
    }

    public function actionDash4($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
        return $this->render('pd-ai-cal', [
            'org_id' => $org_id,
            'country' => $country,
        ]);
    }

    public function actionGraph($graphType = null, $dateRange = null, $animal_type = null, $main_breed = null, $org_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null)
    {
        return $this->renderPartial('graph/_widget', [
            'graphType' => $graphType,
            'dateRange' => $dateRange,
            'graphFilterOptions' => [
                'animal_type' => $animal_type,
                'main_breed' => $main_breed,
                'org_id' => $org_id,
                'region_id' => $region_id,
                'district_id' => $district_id,
                'ward_id' => $ward_id,
                'village_id' => $village_id,
            ],
        ]);
    }
}