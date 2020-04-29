<?php

use backend\controllers\BackendController;
use backend\modules\auth\Session;
use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
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
$this->title = Lang::t('Animals Registered');
$this->params['breadcrumbs'][] = ['label' => Lang::t('Quick Reports'), 'url' => ['dash', 'country_id' => $country->id]];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<h3>
    <?php if (Session::isVillageUser()): ?>
        <?= Lang::t('Animals Registered in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
    <?php elseif (Session::isWardUser()): ?>
        <?= Lang::t('Animals Registered in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
    <?php elseif (Session::isDistrictUser()): ?>
        <?= Lang::t('Animals Registered in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
    <?php elseif (Session::isRegionUser()): ?>
        <?= Lang::t('Animals Registered in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
    <?php elseif (Session::isOrganizationUser()): ?>
        <?= Lang::t('Animals Registered in') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
    <?php elseif (Session::isOrganizationClientUser()): ?>
        <?= Lang::t('Animals Registered in') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
    <?php else: ?>
        <?= Lang::t('Animals Registered in {country}', ['country' => $country->name]); ?>
    <?php endif; ?>
</h3>
<hr>
<div class="row">
    <div class="col-md-6">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="col-md-12 kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__title">
                    <?php if (Session::isWardUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Regions in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isDistrictUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Regions in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isRegionUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Regions in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isOrganizationUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Regions  in') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isOrganizationClientUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Regions in') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php else: ?>
                        <?= Lang::t('Registered Animals Grouped by Regions in {country}', ['country' => $country->name]); ?>
                    <?php endif; ?>
                </div>
                <div id="chartContainer"></div>
                <?php $chart_data = CountriesDashboardStats::getAnimalsGroupedByRegions($country->id); ?>
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
                        <?= Lang::t('Registered Animals Grouped by Breeds in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isWardUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Breeds in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isDistrictUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Breeds in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isRegionUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Breeds in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isOrganizationUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Breeds  in') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isOrganizationClientUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Breeds in') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php else: ?>
                        <?= Lang::t('Registered Animals Grouped by Breeds in {country}', ['country' => $country->name]); ?>
                    <?php endif; ?>
                </div>
                <div id="chartContainer2"></div>
                <?php $chart_data = CountriesDashboardStats::getAnimalsGroupedByBreeds($country->id); ?>
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

        <!--end::Portlet-->
    </div>
    <div class="col-md-6">
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span>
                        <?= CountriesDashboardStats::getAnimalCounts($country->id, Animal::ANIMAL_TYPE_COW) ?>
                    </span>
                </div>
                <div class="kt-iconbox__title">
                    <?= Lang::t('ADGG Number of Cows'); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span>
                        <?= CountriesDashboardStats::getAnimalCounts($country->id, Animal::ANIMAL_TYPE_HEIFER) ?>
                    </span>
                </div>
                <div
                        class="kt-iconbox__title"><?= Lang::t('ADGG Number of Heifers'); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span>
                        <?= CountriesDashboardStats::getAnimalCounts($country->id, Animal::ANIMAL_TYPE_BULL) ?>
                    </span>
                </div>
                <div
                        class="kt-iconbox__title"><?= Lang::t('ADGG Number of Bulls'); ?>
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
                <div
                        class="kt-iconbox__title"><?= Lang::t('ADGG Number of Male Calves'); ?>
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
                <div
                        class="kt-iconbox__title"><?= Lang::t('ADGG Number of Female Calves'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
