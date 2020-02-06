<?php

use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\MilkingReport;
use backend\modules\core\models\Organization;
use backend\modules\core\models\OrganizationUnits;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
/* @var $country Organization */
$controller = Yii::$app->controller;
$this->title = Lang::t('Test Day Milk Report');
$this->params['breadcrumbs'] = [
    $this->title,
];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<div class="row">
    <div class="col-md-12">
        <h3><?= Lang::t('Test Day Milk Report in {country}', ['country' => $country->name]) ?></h3>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">
                            <?= Lang::t('Test Day Milk Records Grouped By Region in {country}', ['country' => $country->name]) ?>
                        </div>
                        <div id="chartContainer" title=""></div>
                        <?php
                        $data = [];

                        $chart_data = CountriesDashboardStats::getTestDayMilkGroupedByRegions($country->id);

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
                        $graphOptions = [
                        ];
                        $containerId = 'chartContainer';
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
                            <span><?= MilkingReport::getFarmersWithAnimalsWithMilkingRecord($country->id) ?></span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?= Lang::t('Number Of Farmers in {country} With Animals With Test Day Milk Record', ['country' => $country->name]); ?>                </div>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= MilkingReport::getAnimalsWithMilkingRecord($country->id) ?></span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?= Lang::t('Number of Animals in {country} With  Test Day Milk Record', ['country' => $country->name]); ?>                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>