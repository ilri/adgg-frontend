<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\Farm;
use backend\modules\core\models\OrganizationUnits;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<div class="row">
    <div class="col-md-12">
        <h3><?= Lang::t('Large Scale Farm Stats') ?></h3>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">Large Scale Farms Grouped By Region</div>
                        <div id="chartContainer" title=""></div>
                        <!--                $this->render('graph/_widget', ['graphType' => HighChart::GRAPH_PIE, 'graphFilterOptions' => $graphFilterOptions])
                        --> <?php
                        $condition = '';
                        $params = [];
                        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
                        $data = [];
                        // get regions
                        $regions = OrganizationUnits::getListData('id', 'name', '', ['org_id' => 10, 'level' => OrganizationUnits::LEVEL_REGION]);
                        //print_r($regions);
                        foreach ($regions as $id => $label) {
                            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

                            // fetch count for each district
                            //print_r(Farm::find()->andWhere($newcondition, $newparams)->createCommand()->rawSql);
                            $count = Farm::find()->where($newcondition, $newparams)
                                ->andWhere(['farm_type' => 'LSF'])
                                ->count();
                            if ($count > 0) {
                                $data[] = [
                                    'name' => $label,
                                    'y' => floatval(number_format($count, 2, '.', '')),
                                ];
                            }

                        };
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
                        <div class="kt-iconbox__title">Large Scale Farms Grouped By Breeds</div>
                        <div id="chartContainer2" title=""></div>
                        <!--                $this->render('graph/_widget', ['graphType' => HighChart::GRAPH_PIE, 'graphFilterOptions' => $graphFilterOptions])
                        --> <?php
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
                                ->count();
                            if ($count > 0) {
                                $labels[] = trim($label);
                                $data[] = floatval($count);
                            }

                        };
                        //print_r($data);
                        $series = [[
                            'colorByPoint' => true,
                            'data' => $data,
                            'name' => 'Breed Details'
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
                                    'margin' => 40,
                                    'text' => 'Breed Type'
                                ],
                            ],
                            'yAxis' => [
                                'categories' => [],
                                'title' => [
                                    'margin' => 40,
                                    'text' => 'Number Of Farms'
                                ],
                            ],
                            'plotOptions' => [
                                'series' => [
                                    //'pointPadding'=>10
                                ]
                            ]
                        ];
                        $containerId = 'chartContainer2';
                        $this->registerJs("MyApp.modules.dashboard.piechart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>