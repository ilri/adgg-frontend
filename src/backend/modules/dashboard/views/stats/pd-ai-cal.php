<?php

use backend\controllers\BackendController;
use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Organization;
use backend\modules\core\models\OrganizationUnits;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller BackendController */
/* @var $graphFilterOptions array */
/* @var $country Organization */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<div class="row">
    <div class="col-md-12">
        <h3><?= Lang::t('Insemination,Pregnancy Diagnosis And Calving in {country}', ['country' => $country->name]) ?></h3>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">
                            <?= Lang::t('Male Calves Registered Grouped By Region in {country}', ['country' => $country->name]) ?>
                        </div>
                        <div id="chartContainer" title=""></div>
                        <?php
                        $data = [];

                        $chart_data = CountriesDashboardStats::getMaleCalvesByRegions($country->id);

                        if (count($chart_data) > 0) {
                            foreach ($chart_data as $cdata){
                                $data[] = [
                                    'name' => $cdata['label'],
                                    'y' => floatval(number_format($cdata['value'], 2, '.', '')),
                                ];
                            }
                        }
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
                        <div class="kt-iconbox__title">
                            <?= Lang::t('Female Calves Registered Grouped By Region in {country}', ['country' => $country->name]) ?>
                        </div>
                        <div id="chartContainer2" title=""></div>
                        <?php
                        $data = [];

                        $chart_data = CountriesDashboardStats::getFemaleCalvesByRegions($country->id);

                        if (count($chart_data) > 0) {
                            foreach ($chart_data as $cdata){
                                $data[] = [
                                    'name' => $cdata['label'],
                                    'y' => floatval(number_format($cdata['value'], 2, '.', '')),
                                ];
                            }
                        }
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
                            <span><?= number_format(AnimalEvent::getCount(['org_id' => $country->id, 'event_type' => AnimalEvent::EVENT_TYPE_CALVING])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?= Lang::t('Total Number Of Calving in {country}', ['country' => $country->name]) ?>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(AnimalEvent::getCount(['org_id' => $country->id, 'event_type' => AnimalEvent::EVENT_TYPE_AI])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?= Lang::t(' Total Number Of Insemination in {country}', ['country' => $country->name]) ?>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(AnimalEvent::getCount(['org_id' => $country->id, 'event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?= Lang::t(' Total Number Of Pregnancy Diagnosis in {country}', ['country' => $country->name]) ?>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(Animal::getCount(['org_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?= Lang::t(' Total Number of Male Calves in {country}', ['country' => $country->name]) ?>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(Animal::getCount(['org_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?= Lang::t(' Total Number of Female Calves in {country}', ['country' => $country->name]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>