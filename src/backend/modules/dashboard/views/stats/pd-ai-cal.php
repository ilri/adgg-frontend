<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\OrganizationUnits;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<div class="row">
    <div class="col-md-12">
        <h3><?= Lang::t('Insemination,Pregnancy Diagnosis And Calving') ?></h3>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">Male Calves Registered Grouped By Region</div>
                        <div id="chartContainer" title=""></div>
                        <!--                $this->render('graph/_widget', ['graphType' => HighChart::GRAPH_PIE, 'graphFilterOptions' => $graphFilterOptions])
                        --> <?php
                        $condition = '';
                        $params = [];
                        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
                        $data = [];
                        // get districts
                        $districts = OrganizationUnits::getListData('id', 'name', '', ['org_id' => 10, 'level' => OrganizationUnits::LEVEL_REGION]);
                        foreach ($districts as $id => $label) {
                            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

                            $count = Animal::find()->where($newcondition, $newparams)
                                ->andWhere(['animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])
                                ->count();
                            if ($count > 0) {
                                $data[] = [
                                    'name' => $label,
                                    'y' => floatval(number_format($count, 2, '.', '')),
                                ];
                            }
                        };
                        $series = [[
                            'colorByPoint' => true,
                            'data' => $data,
                        ]];
                        $graphOptions = [];
                        $containerId = 'chartContainer';
                        $this->registerJs("MyApp.modules.dashboard.piechart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

                        ?>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">Female Calves Registered Grouped By Region</div>
                        <div id="chartContainer2" title=""></div>
                        <!--                $this->render('graph/_widget', ['graphType' => HighChart::GRAPH_PIE, 'graphFilterOptions' => $graphFilterOptions])
                        --> <?php
                        $data = [];
                        // get districts
                        $districts = OrganizationUnits::getListData('id', 'name', '', ['org_id' => 10, 'level' => OrganizationUnits::LEVEL_REGION]);
                        foreach ($districts as $id => $label) {
                            list($newCondition, $newParams) = DbUtils::appendCondition('region_id', $id, $condition, $params);
                            $count = Animal::find()->where($newcondition, $newparams)
                                ->andWhere(['animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])
                                ->count();
                            if ($count > 0) {
                                $data[] = [
                                    'name' => $label,
                                    'y' => floatval(number_format($count, 2, '.', '')),
                                ];
                            }
                        };
                        $series = [[
                            'colorByPoint' => true,
                            'data' => $data,
                        ]];
                        $graphOptions = [];
                        $containerId = 'chartContainer2';
                        $this->registerJs("MyApp.modules.dashboard.piechart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(AnimalEvent::getCount(['org_id' => 10, 'event_type' => AnimalEvent::EVENT_TYPE_CALVING])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">Total Number Of Calving</div>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(AnimalEvent::getCount(['org_id' => 10, 'event_type' => AnimalEvent::EVENT_TYPE_AI])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">Total Number Of Insemination</div>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(AnimalEvent::getCount(['org_id' => 10, 'event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">Total Number Of Pregnancy Diagnosis</div>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(Animal::getCount(['org_id' => 10, 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">Total Number of Male Calves</div>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(Animal::getCount(['org_id' => 10, 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">Total Number of Female Calves</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>