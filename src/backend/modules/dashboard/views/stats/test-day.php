<?php

use backend\controllers\BackendController;
use backend\modules\auth\Session;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\MilkingReport;
use backend\modules\core\models\Country;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller BackendController */
/* @var $graphFilterOptions array */
/* @var $country Country */
/* @var $dataProvider */
$controller = Yii::$app->controller;
$this->title = Lang::t('Test Day Milk');
$this->params['breadcrumbs'][] = ['label' => Lang::t('Quick Reports'), 'url' => ['dash', 'country_id' => $country->id]];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<div class="row">
    <div class="col-md-12">
        <h3>
            <?php if (Session::isVillageUser()): ?>
                <?= Lang::t('Test Day Milk Report in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php elseif (Session::isWardUser()): ?>
                <?= Lang::t('Test Day Milk Report in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php elseif (Session::isDistrictUser()): ?>
                <?= Lang::t('Test Day Milk Report in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php elseif (Session::isRegionUser()): ?>
                <?= Lang::t('Test Day Milk Report in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php else: ?>
                <?= Lang::t('Test Day Milk Report in {country}', ['country' => $country->name]) ?>
            <?php endif; ?>
        </h3>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">
                            <?php if (Session::isWardUser()): ?>
                                <?= Lang::t('Test Day Milk Records Grouped By Villages in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Test Day Milk Records Grouped By Wards in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Test Day Milk Records Grouped By Districts in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t('Test Day Milk Records Grouped By Region in {country}', ['country' => $country->name]) ?>
                            <?php endif; ?>
                        </div>
                        <div id="chartContainer" title=""></div>
                        <?php if (Session::isWardUser()): ?>
                            <?php $chart_data = CountriesDashboardStats::getTestDayMilkGroupedByVillages($country->id, Session::getWardId()); ?>
                        <?php elseif (Session::isDistrictUser()): ?>
                            <?php $chart_data = CountriesDashboardStats::getTestDayMilkGroupedByWards($country->id, Session::getDistrictId()); ?>
                        <?php elseif (Session::isRegionUser()): ?>
                            <?php $chart_data = CountriesDashboardStats::getTestDayMilkGroupedByDistricts($country->id, Session::getRegionId()); ?>
                        <?php else: ?>
                            <?php $chart_data = CountriesDashboardStats::getTestDayMilkGroupedByRegions($country->id); ?>
                        <?php endif; ?>
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
            </div>
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span>
                                <?php if (Session::isVillageUser()): ?>
                                    <?= MilkingReport::getFarmersWithAnimalsWithMilkingRecord($country->id, ['core_animal.village_id' => Session::getVillageId()]) ?>
                                <?php elseif (Session::isWardUser()): ?>
                                    <?= MilkingReport::getFarmersWithAnimalsWithMilkingRecord($country->id, ['core_animal.ward_id' => Session::getWardId()]) ?>
                                <?php elseif (Session::isDistrictUser()): ?>
                                    <?= MilkingReport::getFarmersWithAnimalsWithMilkingRecord($country->id, ['core_animal.district_id' => Session::getDistrictId()]) ?>
                                <?php elseif (Session::isRegionUser()): ?>
                                    <?= MilkingReport::getFarmersWithAnimalsWithMilkingRecord($country->id, ['core_animal.region_id' => Session::getRegionId()]) ?>
                                <?php else: ?>
                                    <?= MilkingReport::getFarmersWithAnimalsWithMilkingRecord($country->id) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?php if (Session::isVillageUser()): ?>
                                <?= Lang::t('Number Of Farmers  With Cows With Test Day Milk Record in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isWardUser()): ?>
                                <?= Lang::t('Number Of Farmers  With Cows With Test Day Milk Record in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Number Of Farmers  With Cows With Test Day Milk Record in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Number Of Farmers  With Cows With Test Day Milk Record in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t('Test Day Milk Records Grouped By Region in {country}', ['country' => $country->name]) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span>
                                <?php if (Session::isVillageUser()): ?>
                                    <?= MilkingReport::getAnimalsWithMilkingRecord($country->id, ['core_animal_event.village_id' => Session::getVillageId()]) ?>
                                <?php elseif (Session::isWardUser()): ?>
                                    <?= MilkingReport::getAnimalsWithMilkingRecord($country->id, ['core_animal_event.ward_id' => Session::getWardId()]) ?>
                                <?php elseif (Session::isDistrictUser()): ?>
                                    <?= MilkingReport::getAnimalsWithMilkingRecord($country->id, ['core_animal_event.district_id' => Session::getDistrictId()]) ?>
                                <?php elseif (Session::isRegionUser()): ?>
                                    <?= MilkingReport::getAnimalsWithMilkingRecord($country->id, ['core_animal_event.region_id' => Session::getRegionId()]) ?>
                                <?php else: ?>
                                    <?= MilkingReport::getAnimalsWithMilkingRecord($country->id) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?php if (Session::isVillageUser()): ?>
                                <?= Lang::t('Number of Cows  With  Test Day Milk Record in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isWardUser()): ?>
                                <?= Lang::t('Number of Cows  With  Test Day Milk Record in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Number of Cows  With  Test Day Milk Record in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Number of Cows  With  Test Day Milk Record in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t('Number of Cows  With  Test Day Milk Record in {country}', ['country' => $country->name]); ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <?= $this->render('_animalGrid', ['dataProvider' => $dataProvider]) ?>
    </div>
</div>