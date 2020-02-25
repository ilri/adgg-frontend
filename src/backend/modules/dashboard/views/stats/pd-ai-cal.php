<?php

use backend\controllers\BackendController;
use backend\modules\auth\Session;
use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller BackendController */
/* @var $graphFilterOptions array */
/* @var $country Country */
$controller = Yii::$app->controller;
$this->title = Lang::t('Insemination,PD And Calving');
$this->params['breadcrumbs'][] = ['label' => Lang::t('Quick Reports'), 'url' => ['dash']];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<div class="row">
    <div class="col-md-12">
        <h3>
            <?php if (Session::isVillageUser()): ?>
                <?= Lang::t('Insemination,Pregnancy Diagnosis And Calving in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php elseif (Session::isWardUser()): ?>
                <?= Lang::t('Insemination,Pregnancy Diagnosis And Calving in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php elseif (Session::isDistrictUser()): ?>
                <?= Lang::t('Insemination,Pregnancy Diagnosis And Calving in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php elseif (Session::isRegionUser()): ?>
                <?= Lang::t('Insemination,Pregnancy Diagnosis And Calving in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php else: ?>
                <?= Lang::t('Insemination,Pregnancy Diagnosis And Calving in {country}', ['country' => $country->name]) ?>
            <?php endif; ?>
        </h3>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">
                            <?php if (Session::isVillageUser()): ?>
                                <?= Lang::t('Male Calves Registered Grouped By Region in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isWardUser()): ?>
                                <?= Lang::t('Male Calves Registered Grouped By Region in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Male Calves Registered Grouped By Region in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Male Calves Registered Grouped By Region in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t('Male Calves Registered Grouped By Region in {country}', ['country' => $country->name]) ?>
                            <?php endif; ?>
                        </div>
                        <div id="chartContainer" title=""></div>
                        <?php if (Session::isWardUser()): ?>
                            <?php $chart_data = CountriesDashboardStats::getMaleCalvesGroupedByVillages($country->id, Session::getRegionId(), Session::getDistrictId(), Session::getWardId()); ?>
                        <?php elseif (Session::isDistrictUser()): ?>
                            <?php $chart_data = CountriesDashboardStats::getMaleCalvesGroupedByWards($country->id, Session::getRegionId(), Session::getDistrictId()); ?>
                        <?php elseif (Session::isRegionUser()): ?>
                            <?php $chart_data = CountriesDashboardStats::getMaleCalvesGroupedByDistricts($country->id, Session::getRegionId()); ?>
                        <?php else: ?>
                            <?php $chart_data = CountriesDashboardStats::getMaleCalvesByRegions($country->id); ?>
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
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">
                            <?php if (Session::isWardUser()): ?>
                                <?= Lang::t('Female  Calves Registered Grouped By Villages in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Female  Calves Registered Grouped By Wards in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Female  Calves Registered Grouped By Districts in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t('Female  Calves Registered Grouped By Region in {country}', ['country' => $country->name]) ?>
                            <?php endif; ?>
                        </div>
                        <div id="chartContainer2" title=""></div>
                        <?php if (Session::isWardUser()): ?>
                            <?php $chart_data = CountriesDashboardStats::getFemaleCalvesGroupedByVillages($country->id, Session::getWardId()); ?>
                        <?php elseif (Session::isDistrictUser()): ?>
                            <?php $chart_data = CountriesDashboardStats::getFemaleCalvesGroupedByWards($country->id, Session::getDistrictId()); ?>
                        <?php elseif (Session::isRegionUser()): ?>
                            <?php $chart_data = CountriesDashboardStats::getFemaleCalvesGroupedByDistricts($country->id, Session::getRegionId()); ?>
                        <?php else: ?>
                            <?php $chart_data = CountriesDashboardStats::getFemaleCalvesByRegions($country->id); ?>
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
                            <span><?= number_format(AnimalEvent::getCount(['country_id' => $country->id, 'event_type' => AnimalEvent::EVENT_TYPE_CALVING])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?php if (Session::isVillageUser()): ?>
                                <?= Lang::t('Total Number Of Calving in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isWardUser()): ?>
                                <?= Lang::t('Total Number Of Calving in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Total Number Of Calving in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Total Number Of Calving in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t('Total Number Of Calving in {country}', ['country' => $country->name]) ?>
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
                                    <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'village_id' => Session::getVillageId(), 'event_type' => AnimalEvent::EVENT_TYPE_AI])) ?>
                                <?php elseif (Session::isWardUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'ward_id' => Session::getWardId(), 'event_type' => AnimalEvent::EVENT_TYPE_AI])) ?>
                                <?php elseif (Session::isDistrictUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'district_id' => Session::getDistrictId(), 'event_type' => AnimalEvent::EVENT_TYPE_AI])) ?>
                                <?php elseif (Session::isRegionUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'region_id' => Session::getRegionId(), 'event_type' => AnimalEvent::EVENT_TYPE_AI])) ?>
                                <?php else: ?>
                                    <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'event_type' => AnimalEvent::EVENT_TYPE_AI])) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?php if (Session::isVillageUser()): ?>
                                <?= Lang::t('Total Number Of Insemination in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isWardUser()): ?>
                                <?= Lang::t('Total Number Of Insemination in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Total Number Of Insemination in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Total Number Of Insemination in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t(' Total Number Of Insemination in {country}', ['country' => $country->name]) ?>
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
                                    <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'village_id' => Session::getVillageId(), 'event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS])) ?>
                                <?php elseif (Session::isWardUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'ward_id' => Session::getWardId(), 'event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS])) ?>
                                <?php elseif (Session::isDistrictUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'district_id' => Session::getDistrictId(), 'event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS])) ?>
                                <?php elseif (Session::isRegionUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'region_id' => Session::getRegionId(), 'event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS])) ?>
                                <?php else: ?>
                                    <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS])) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?php if (Session::isVillageUser()): ?>
                                <?= Lang::t('Total Number Of Pregnancy Diagnosis in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isWardUser()): ?>
                                <?= Lang::t('Total Number Of Pregnancy Diagnosis in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Total Number Of Pregnancy Diagnosis in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Total Number Of Pregnancy Diagnosis in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t(' Total Number Of Pregnancy Diagnosis in {country}', ['country' => $country->name]) ?>
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
                                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'village_id' => Session::getVillageId(), 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])) ?>
                                <?php elseif (Session::isWardUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'ward_id' => Session::getWardId(), 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])) ?>
                                <?php elseif (Session::isDistrictUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'district_id' => Session::getDistrictId(), 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])) ?>
                                <?php elseif (Session::isRegionUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'region_id' => Session::getRegionId(), 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])) ?>
                                <?php else: ?>
                                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?php if (Session::isVillageUser()): ?>
                                <?= Lang::t('Total Number Of Male Calves in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isWardUser()): ?>
                                <?= Lang::t('Total Number Of Male Calves in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Total Number Of Male Calves in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Total Number Of Male Calves in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t(' Total Number of Male Calves in {country}', ['country' => $country->name]) ?>
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
                                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'village_id' => Session::getVillageId(), 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])) ?>
                                <?php elseif (Session::isWardUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'ward_id' => Session::getWardId(), 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])) ?>
                                <?php elseif (Session::isDistrictUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'district_id' => Session::getDistrictId(), 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])) ?>
                                <?php elseif (Session::isRegionUser()): ?>
                                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'region_id' => Session::getRegionId(), 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])) ?>
                                <?php else: ?>
                                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?php if (Session::isVillageUser()): ?>
                                <?= Lang::t('Total Number Of  Female Calves in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isWardUser()): ?>
                                <?= Lang::t('Total Number Of  Female Calves in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Total Number Of  Female Calves in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Total Number Of  Female Calves in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t(' Total Number of Female Calves in {country}', ['country' => $country->name]) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>