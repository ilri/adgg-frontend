<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Farm;
use backend\modules\core\models\OrganizationRef;
use backend\modules\core\models\OrganizationRefUnits;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
/* @var $country OrganizationRef */
/* @var $dataProvider */

$controller = Yii::$app->controller;
$this->title = Lang::t('Large Scale Farm Report');
$this->params['breadcrumbs'] = [
    $this->title,
];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<div class="row">
    <div class="col-md-12">
        <h3><?= Lang::t('Large Scale Farm Report in {country}', ['country' => $country->name]) ?></h3>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">
                            <?= Lang::t('Large Scale Farms Grouped By Region in {country}', ['country' => $country->name]) ?>
                        </div>
                        <div id="chartContainer" title=""></div>
                        <?php
                        $data = [];

                        $chart_data = CountriesDashboardStats::getLSFGroupedByRegions($country->id);

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
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">
                            <?= Lang::t('Number of Animals Registered By Breeds in {country}', ['country' => $country->name]) ?>
                        </div>
                        <div id="chartContainer2" title=""></div>
                        <?php
                        $condition = '';
                        $params = [];
                        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
                        $data = [];
                        $labels = [];
                        // get breeds
                        $breeds = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
                        foreach ($breeds as $id => $label) {
                            list($newCondition, $newParams) = DbUtils::appendCondition('main_breed', $id, $condition, $params);
                            $count = Animal::find()->joinWith('farm')
                                ->andWhere($newCondition, $newParams)
                                ->andWhere([Farm::tableName() . '.farm_type' => 'LSF'])
                                ->andWhere([Farm::tableName() . '.country_id' => $country->id])
                                ->count();
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