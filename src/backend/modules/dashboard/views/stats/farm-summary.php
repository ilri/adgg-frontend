<?php

use backend\controllers\BackendController;
use backend\modules\auth\Session;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Farm;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller BackendController */
/* @var $graphFilterOptions array */
/* @var $country Country */
$controller = Yii::$app->controller;
$this->title = 'Farms Registered';
$this->params['breadcrumbs'][] = ['label' => Lang::t('Quick Reports'), 'url' => ['dash', 'country_id' => $country->id]];
$graphType = $graphType ?? HighChart::GRAPH_PIE;

?>
<h3>
    <?php if (Session::isVillageUser()): ?>
        <?= Lang::t('Farms Registered in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
    <?php elseif (Session::isWardUser()): ?>
        <?= Lang::t('Farms Registered in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
    <?php elseif (Session::isDistrictUser()): ?>
        <?= Lang::t('Farms Registered in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
    <?php elseif (Session::isRegionUser()): ?>
        <?= Lang::t('Farms Registered in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
    <?php else: ?>
        <?= Lang::t('Farms Registered in {country}', ['country' => $country->name]) ?>
    <?php endif; ?>
</h3>
<hr>
<div class="row">
    <div class="col-lg-6 col-md-7 col-sm-8">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="col-md-12 kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__title">
                    <?php if (Session::isWardUser()): ?>
                        <?= Lang::t('Registered Farms Grouped by Villages in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isDistrictUser()): ?>
                        <?= Lang::t('Registered Farms Grouped by Wards in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isRegionUser()): ?>
                        <?= Lang::t('Registered Farms Grouped by Districts in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php else: ?>
                        <?= Lang::t('Registered Farms Grouped by Regions  in {country}', ['country' => $country->name]) ?>
                    <?php endif; ?>
                </div>
                <div id="chartContainer"></div>
                <?php $chart_data = CountriesDashboardStats::getFarmsGroupedByRegions($country->id); ?>
                <?php
                $data = [];
                if (count($chart_data) > 0) {
                    foreach ($chart_data as $cdata) {
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
                    <?php if (Session::isVillageUser()): ?>
                        <?= Lang::t('Registered Farms Grouped by Farm Type in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isWardUser()): ?>
                        <?= Lang::t('Registered Farms Grouped by Farm Type in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isDistrictUser()): ?>
                        <?= Lang::t('Registered Farms Grouped by Farm Type in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isRegionUser()): ?>
                        <?= Lang::t('Registered Farms Grouped by Farm Type in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php else: ?>
                        <?= Lang::t('Registered Farms Grouped by Farm Type in {country}', ['country' => $country->name]) ?>
                    <?php endif; ?>
                </div>
                <div id="chartContainer2"></div>
                <?php $chart_data = CountriesDashboardStats::getFarmsGroupedByFarmType($country->id); ?>
                <?php
                $data = [];
                if (count($chart_data) > 0) {
                    foreach ($chart_data as $cdata) {
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
            <!--end::Portlet-->
        </div>
    </div>
    <div class=" col-lg-6 col-md-5 col-sm-4">
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span>
                        <?= CountriesDashboardStats::getFarmCounts($country->id, false) ?>
                    </span>
                </div>
                <div class="kt-iconbox__title">
                    <?= Lang::t('Number Of Farms'); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span>
                        <?= CountriesDashboardStats::getFarmCounts($country->id, true, 1) ?>
                    </span>
                </div>
                <div class="kt-iconbox__title">
                    <?= Lang::t('Male Household headed Farmers'); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span>
                        <?= CountriesDashboardStats::getFarmCounts($country->id, true, 2) ?>
                    </span>
                </div>
                <div
                        class="kt-iconbox__title"><?= Lang::t('Female Household headed Farmers'); ?></div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span>
                        <?= CountriesDashboardStats::getFarmCounts($country->id, true, [1, 2]) ?>
                    </span>
                </div>
                <div class="kt-iconbox__title">
                    <?= Lang::t('Households headed by both male and female members'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
