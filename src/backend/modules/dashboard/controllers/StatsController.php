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
        $searchModel = Animal::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        return $this->render('dashboards', [
            'graphFilterOptions' => [
                'org_id' => $org_id,
            ],
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDash1()
    {
        $dataProvider = LSFMilkingReport::getLargeScaleFarmMilkDetails();
        return $this->render('lsf', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDash2($org_id = null)
    {
        return $this->render('test-day', [
            'graphFilterOptions' => [
                'org_id' => $org_id,
            ],
        ]);
    }

    public function actionDash3($org_id = null)
    {
        return $this->render('genotyped-animals', [
            'graphFilterOptions' => [
                'org_id' => $org_id,
            ],
        ]);
    }

    public function actionDash4($org_id = null)
    {
        return $this->render('pd-ai-cal', [
            'graphFilterOptions' => [
                'org_id' => $org_id,
            ],
        ]);
    }

    public function actionFarmSummary()
    {
        return $this->render('farm-summary', [
            'graphFilterOptions' => [
                'org_id' => 10,
            ],
        ]);
    }

    public function actionAnimalSummary()
    {
        return $this->render('animal-summary', [
            'graphFilterOptions' => [
                'org_id' => 10,
            ],
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