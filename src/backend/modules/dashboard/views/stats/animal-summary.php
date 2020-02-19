<?php

use backend\controllers\BackendController;
use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller BackendController */
/* @var $graphFilterOptions array */
/* @var $country Country */
$controller = Yii::$app->controller;
$this->title = Lang::t('Animals Registered Report');
$this->params['breadcrumbs'] = [
    $this->title,
];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<h3><?= Lang::t('Animals Registered in {country}', ['country' => $country->name]); ?></h3>
<hr>
<div class="row">
    <div class="col-md-6">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="col-md-12 kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__title">
                    <?= Lang::t('Registered Animals Grouped by Regions in {country}', ['country' => $country->name]); ?>
                </div>
                <div id="chartContainer"></div>
                <?php
                $data = [];

                $chart_data = CountriesDashboardStats::getAnimalsGroupedByRegions($country->id);

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
                    <?= Lang::t('Registered Animals Grouped by Breeds in {country}', ['country' => $country->name]); ?>
                </div>
                <div id="chartContainer2"></div>
                <?php
                $data = [];

                $chart_data = CountriesDashboardStats::getAnimalsGroupedByBreeds($country->id);

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

        <!--end::Portlet-->
    </div>
    <div class="col-md-6">
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Animal::getCount(['country_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_COW])) ?></span>
                </div>
                <div class="kt-iconbox__title">
                    <?= Lang::t('ADGG Number of Cows in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Animal::getCount(['country_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_HEIFER])) ?></span>
                </div>
                <div
                    class="kt-iconbox__title"><?= Lang::t('ADGG Number of Heifers in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Animal::getCount(['country_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_BULL])) ?></span>
                </div>
                <div
                    class="kt-iconbox__title"><?= Lang::t('ADGG Number of Bulls in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Animal::getCount(['country_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])) ?></span>
                </div>
                <div
                    class="kt-iconbox__title"><?= Lang::t('ADGG Number of Male Calves in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Animal::getCount(['country_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])) ?></span>
                </div>
                <div
                    class="kt-iconbox__title"><?= Lang::t('ADGG Number of Female Calves in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
