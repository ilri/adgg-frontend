<?php

use backend\controllers\BackendController;
use backend\modules\auth\Session;
use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Client;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Farm;
use backend\modules\core\models\Organization;
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
$this->params['breadcrumbs'][] = ['label' => Lang::t('Quick Reports'), 'url' => ['dash', 'country_id' => $country->id]];
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
            <?php elseif (Session::isOrganizationUser()): ?>
                <?= Lang::t('Insemination,Pregnancy Diagnosis And Calving  in') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php elseif (Session::isOrganizationClientUser()): ?>
                <?= Lang::t('Insemination,Pregnancy Diagnosis And Calving in') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
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
                            <?php elseif (Session::isOrganizationUser()): ?>
                                <?= Lang::t('Male Calves Registered Grouped By Region Report  in') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isOrganizationClientUser()): ?>
                                <?= Lang::t('Male Calves Registered Grouped By Region in') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t('Male Calves Registered Grouped By Region in {country}', ['country' => $country->name]) ?>
                            <?php endif; ?>
                        </div>
                        <div id="chartContainer" title=""></div>
                        <?php $chart_data = CountriesDashboardStats::getCalvesByRegions(Animal::ANIMAL_TYPE_MALE_CALF, $country->id); ?>
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
                            <?php elseif (Session::isOrganizationUser()): ?>
                                <?= Lang::t('Female Calves Registered Grouped By Region in') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isOrganizationClientUser()): ?>
                                <?= Lang::t('Female Calves Registered Grouped By Region in') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t('Female  Calves Registered Grouped By Region in {country}', ['country' => $country->name]) ?>
                            <?php endif; ?>
                        </div>
                        <div id="chartContainer2" title=""></div>
                        <?php $chart_data = CountriesDashboardStats::getCalvesByRegions(Animal::ANIMAL_TYPE_FEMALE_CALF, $country->id); ?>
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
                            <span>
                             <?= CountriesDashboardStats::getEventCounts($country->id, AnimalEvent::EVENT_TYPE_CALVING) ?>

                            </span>
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
                            <?php elseif (Session::isOrganizationUser()): ?>
                                <?= Lang::t('Total Number Of Calving  in') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isOrganizationClientUser()): ?>
                                <?= Lang::t('Total Number Of Calving in') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
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
                             <?= CountriesDashboardStats::getEventCounts($country->id, AnimalEvent::EVENT_TYPE_AI) ?>
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
                            <?php elseif (Session::isOrganizationUser()): ?>
                                <?= Lang::t('Total Number Of Insemination in') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isOrganizationClientUser()): ?>
                                <?= Lang::t('Total Number Of Insemination in') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
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
                              <?= CountriesDashboardStats::getEventCounts($country->id, AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS) ?>

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
                            <?php elseif (Session::isOrganizationUser()): ?>
                                <?= Lang::t('Total Number Of Pregnancy Diagnosis  in') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isOrganizationClientUser()): ?>
                                <?= Lang::t('Total Number Of Pregnancy Diagnosis in') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
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
                                <?= CountriesDashboardStats::getAnimalCounts($country->id, Animal::ANIMAL_TYPE_MALE_CALF) ?>
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
                            <?php elseif (Session::isOrganizationUser()): ?>
                                <?= Lang::t('Total Number Of Male Calves  in') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isOrganizationClientUser()): ?>
                                <?= Lang::t('Total Number Of Male Calves in') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
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
                                <?= CountriesDashboardStats::getAnimalCounts($country->id, Animal::ANIMAL_TYPE_FEMALE_CALF) ?>
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
                            <?php elseif (Session::isOrganizationUser()): ?>
                                <?= Lang::t('Total Number Of Female Calves  in') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isOrganizationClientUser()): ?>
                                <?= Lang::t('Total Number Of Female Calves in') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
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