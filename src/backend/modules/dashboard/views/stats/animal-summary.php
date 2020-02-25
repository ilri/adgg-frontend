<?php

use backend\controllers\BackendController;
use backend\modules\auth\Session;
use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Farm;
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
$this->params['breadcrumbs'][] = ['label' => Lang::t('Quick Reports'), 'url' => ['dash']];
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
                        <?= Lang::t('Registered Animals Grouped by Villages in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isDistrictUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Wards in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isRegionUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Districts in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php else: ?>
                        <?= Lang::t('Registered Animals Grouped by Regions in {country}', ['country' => $country->name]); ?>
                    <?php endif; ?>
                </div>
                <div id="chartContainer"></div>
                <?php if (Session::isWardUser()): ?>
                    <?php $chart_data = CountriesDashboardStats::getAnimalsGroupedByVillages($country->id, Session::getWardId()); ?>
                <?php elseif (Session::isDistrictUser()): ?>
                    <?php $chart_data = CountriesDashboardStats::getAnimalsGroupedByWards($country->id, Session::getDistrictId()); ?>
                <?php elseif (Session::isRegionUser()): ?>
                    <?php $chart_data = CountriesDashboardStats::getAnimalsGroupedByDistricts($country->id, Session::getRegionId()); ?>
                <?php else: ?>
                    <?php $chart_data = CountriesDashboardStats::getAnimalsGroupedByRegions($country->id); ?>
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
                    <?php if (Session::isVillageUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Breeds in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isWardUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Breeds in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isDistrictUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Breeds in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php elseif (Session::isRegionUser()): ?>
                        <?= Lang::t('Registered Animals Grouped by Breeds in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                    <?php else: ?>
                        <?= Lang::t('Registered Animals Grouped by Breeds in {country}', ['country' => $country->name]); ?>
                    <?php endif; ?>
                </div>
                <div id="chartContainer2"></div>
                <?php if (Session::isVillageUser()): ?>
                    <?php $chart_data = CountriesDashboardStats::getAnimalsGroupedByBreeds($country->id, ['village_id' => Session::getVillageId()]); ?>
                <?php elseif (Session::isWardUser()): ?>
                    <?php $chart_data = CountriesDashboardStats::getAnimalsGroupedByBreeds($country->id, ['ward_id' => Session::getWardId()]); ?>
                <?php elseif (Session::isDistrictUser()): ?>
                    <?php $chart_data = CountriesDashboardStats::getAnimalsGroupedByBreeds($country->id, ['district_id' => Session::getDistrictId()]); ?>
                <?php elseif (Session::isRegionUser()): ?>
                    <?php $chart_data = CountriesDashboardStats::getAnimalsGroupedByBreeds($country->id, ['region_id' => Session::getRegionId()]); ?>
                <?php else: ?>
                    <?php $chart_data = CountriesDashboardStats::getAnimalsGroupedByBreeds($country->id); ?>
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

        <!--end::Portlet-->
    </div>
    <div class="col-md-6">
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span>
                        <?php if (Session::isVillageUser()): ?>
                            <?= Yii::$app->formatter->asDecimal(Animal::find()->andFilterWhere(['country_id' => $country->id, 'village_id' => Session::getVillageId(), 'animal_type' => Animal::ANIMAL_TYPE_COW])->count()) ?>
                        <?php elseif (Session::isWardUser()): ?>
                            <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'ward_id' => Session::getWardId(), 'animal_type' => Animal::ANIMAL_TYPE_COW])) ?>
                        <?php elseif (Session::isDistrictUser()): ?>
                            <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'district_id' => Session::getDistrictId(), 'animal_type' => Animal::ANIMAL_TYPE_COW])) ?>
                        <?php elseif (Session::isRegionUser()): ?>
                            <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'region_id' => Session::getRegionId(), 'animal_type' => Animal::ANIMAL_TYPE_COW])) ?>
                        <?php else: ?>
                            <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_COW])) ?>
                        <?php endif; ?>
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
                          <?php if (Session::isVillageUser()): ?>
                              <?= Yii::$app->formatter->asDecimal(Animal::find()->andFilterWhere(['country_id' => $country->id, 'village_id' => Session::getVillageId(), 'animal_type' => Animal::ANIMAL_TYPE_HEIFER])->count()) ?>
                          <?php elseif (Session::isWardUser()): ?>
                              <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'ward_id' => Session::getWardId(), 'animal_type' => Animal::ANIMAL_TYPE_HEIFER])) ?>
                          <?php elseif (Session::isDistrictUser()): ?>
                              <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'district_id' => Session::getDistrictId(), 'animal_type' => Animal::ANIMAL_TYPE_HEIFER])) ?>
                          <?php elseif (Session::isRegionUser()): ?>
                              <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'region_id' => Session::getRegionId(), 'animal_type' => Animal::ANIMAL_TYPE_HEIFER])) ?>
                          <?php else: ?>
                              <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_HEIFER])) ?>
                          <?php endif; ?>
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
                      <?php if (Session::isVillageUser()): ?>
                          <?= Yii::$app->formatter->asDecimal(Animal::find()->andFilterWhere(['country_id' => $country->id, 'village_id' => Session::getVillageId(), 'animal_type' => Animal::ANIMAL_TYPE_BULL])->count()) ?>
                      <?php elseif (Session::isWardUser()): ?>
                          <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'ward_id' => Session::getWardId(), 'animal_type' => Animal::ANIMAL_TYPE_BULL])) ?>
                      <?php elseif (Session::isDistrictUser()): ?>
                          <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'district_id' => Session::getDistrictId(), 'animal_type' => Animal::ANIMAL_TYPE_BULL])) ?>
                      <?php elseif (Session::isRegionUser()): ?>
                          <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'region_id' => Session::getRegionId(), 'animal_type' => Animal::ANIMAL_TYPE_BULL])) ?>
                      <?php else: ?>
                          <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_BULL])) ?>
                      <?php endif; ?>
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
                         <?php if (Session::isVillageUser()): ?>
                             <?= Yii::$app->formatter->asDecimal(Animal::find()->andFilterWhere(['country_id' => $country->id, 'village_id' => Session::getVillageId(), 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])->count()) ?>
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
                         <?php if (Session::isVillageUser()): ?>
                             <?= Yii::$app->formatter->asDecimal(Animal::find()->andFilterWhere(['country_id' => $country->id, 'village_id' => Session::getVillageId(), 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])->count()) ?>
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
                <div
                        class="kt-iconbox__title"><?= Lang::t('ADGG Number of Female Calves'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
