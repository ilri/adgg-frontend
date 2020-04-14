<?php

use backend\controllers\BackendController;
use backend\modules\auth\Session;
use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Farm;
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
/* @var $dataProvider */

$controller = Yii::$app->controller;
$this->title = Lang::t('Large Scale Farm Report');
$this->params['breadcrumbs'][] = ['label' => Lang::t('Quick Reports'), 'url' => ['dash', 'country_id' => $country->id]];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<div class="row">
    <div class="col-md-12">
        <h3>
            <?php if (Session::isVillageUser()): ?>
                <?= Lang::t('Large Scale Farm Report in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php elseif (Session::isWardUser()): ?>
                <?= Lang::t('Large Scale Farm Report in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php elseif (Session::isDistrictUser()): ?>
                <?= Lang::t('Large Scale Farm Report in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php elseif (Session::isRegionUser()): ?>
                <?= Lang::t('Large Scale Farm Report in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
            <?php else: ?>
                <?= Lang::t('Large Scale Farm Report in {country}', ['country' => $country->name]) ?>
            <?php endif; ?>
        </h3>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">
                            <?php if (Session::isWardUser()): ?>
                                <?= Lang::t('Large Scale Farms Grouped By Region in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Large Scale Farms Grouped By Region in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Large Scale Farms Grouped By Region in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t('Large Scale Farms Grouped By Region in {country}', ['country' => $country->name]) ?>
                            <?php endif; ?>
                        </div>
                        <div id="chartContainer" title=""></div>
                        <?php $chart_data = CountriesDashboardStats::getLSFGroupedByRegions($country->id); ?>
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
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">
                            <?php if (Session::isVillageUser()): ?>
                                <?= Lang::t('Number of Animals Registered By Breeds  in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isWardUser()): ?>
                                <?= Lang::t('Number of Animals Registered By Breeds  in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isDistrictUser()): ?>
                                <?= Lang::t('Number of Animals Registered By Breeds  in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php elseif (Session::isRegionUser()): ?>
                                <?= Lang::t('Number of Animals Registered By Breeds  in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                            <?php else: ?>
                                <?= Lang::t('Number of Animals Registered By Breeds in {country}', ['country' => $country->name]) ?>
                            <?php endif; ?>
                        </div>
                        <div id="chartContainer2" title=""></div>
                        <?php
                        $condition = '';
                        $params = [];
                        //list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
                        $data = [];
                        $labels = [];
                        // get breeds
                        $breeds = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
                        foreach ($breeds as $id => $label) {
                            list($newCondition, $newParams) = DbUtils::appendCondition('main_breed', $id, $condition, $params);
                            if (Session::isVillageUser()) {
                                $count = Animal::find()->joinWith('farm')
                                    ->andWhere($newCondition, $newParams)
                                    ->andFilterWhere([Farm::tableName() . '.farm_type' => 'LSF'])
                                    ->andFilterWhere([Farm::tableName() . '.country_id' => $country->id])
                                    ->andFilterWhere([Farm::tableName() . '.village_id' => Session::getVillageId()])
                                    ->count();
                            } elseif (Session::isWardUser()) {
                                $count = Animal::find()->joinWith('farm')
                                    ->andWhere($newCondition, $newParams)
                                    ->andFilterWhere([Farm::tableName() . '.farm_type' => 'LSF'])
                                    ->andFilterWhere([Farm::tableName() . '.country_id' => $country->id])
                                    ->andFilterWhere([Farm::tableName() . 'ward_id' => Session::getWardId()])
                                    ->count();
                            } elseif (Session::isDistrictUser()) {
                                $count = Animal::find()->joinWith('farm')
                                    ->andWhere($newCondition, $newParams)
                                    ->andFilterWhere([Farm::tableName() . '.farm_type' => 'LSF'])
                                    ->andFilterWhere([Farm::tableName() . '.country_id' => $country->id])
                                    ->andFilterWhere([Farm::tableName() . '.district_id' => Session::getDistrictId()])
                                    ->count();
                            } elseif (Session::isRegionUser()) {
                                $count = Animal::find()->joinWith('farm')
                                    ->andWhere($newCondition, $newParams)
                                    ->andFilterWhere([Farm::tableName() . '.farm_type' => 'LSF'])
                                    ->andFilterWhere([Farm::tableName() . '.country_id' => $country->id])
                                    ->andFilterWhere([Farm::tableName() . '.region_id' => Session::getRegionId()])
                                    ->count();
                            } else {
                                $count = Animal::find()->joinWith('farm')
                                    ->andWhere($newCondition, $newParams)
                                    ->andFilterWhere([Farm::tableName() . '.farm_type' => 'LSF'])
                                    ->andFilterWhere([Farm::tableName() . '.country_id' => $country->id])
                                    ->count();
                            }

                            if ($count > 0) {
                                $labels[] = trim($label);
                                $data[] = floatval($count);
                            }

                        };
                        $series = [[
                            'colorByPoint' => true,
                            'data' => $data,
                            'showInLegend' => false,
                        ]];
                        $graphOptions = [
                            'chart' => [
                                'type' => 'column',
                            ],
                            'tooltip' => [
                                'pointFormat' => '{point.y}',
                            ],
                            'xAxis' => [
                                'categories' => $labels,
                                'title' => [
                                    'margin' => 30,
                                    'text' => 'Breed Type'
                                ],
                            ],
                            'yAxis' => [
                                'categories' => [],
                                'title' => [
                                    'margin' => 40,
                                    'text' => 'Number Of Animals'
                                ],
                            ],
                        ];
                        $containerId = 'chartContainer2';
                        $this->registerJs("MyApp.modules.dashboard.piechart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <?= $this->render('_grid', ['dataProvider' => $dataProvider]) ?>
    </div>
</div>