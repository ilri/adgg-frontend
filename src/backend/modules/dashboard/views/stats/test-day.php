<?php

use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\MilkingReport;
use backend\modules\core\models\Organization;
use backend\modules\core\models\OrganizationUnits;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
/* @var $country Organization */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<div class="row">
    <div class="col-md-12">
        <h3><?= Lang::t('Test Day Milk Report in {country}', ['country' => $country->name]) ?></h3>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__title">
                            <?= Lang::t('Test Day Milk Records Grouped By Region in {country}', ['country' => $country->name]) ?>
                        </div>
                        <div id="chartContainer" title=""></div>
                        <?php
                        $condition = '';
                        $params = [];
                        list($condition, $params) = AnimalEvent::appendOrgSessionIdCondition($condition, $params);
                        $data = [];
                        // get regions
                        $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
                        //print_r($regions);
                        foreach ($regions as $id => $label) {
                            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

                            $count = AnimalEvent::find()->where($newcondition, $newparams)
                                ->andWhere(['event_type' => AnimalEvent::EVENT_TYPE_MILKING, 'org_id' => $country->id])
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
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= MilkingReport::getFarmersWithAnimalsWithMilkingRecord($country->id) ?></span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?= Lang::t('Number Of Farmers in {country} With Animals With Test Day Milk Record', ['country' => $country->name]); ?>                </div>
                    </div>
                </div>
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= MilkingReport::getAnimalsWithMilkingRecord($country->id) ?></span>
                        </div>
                        <div class="kt-iconbox__title">
                            <?= Lang::t('Number of Animals in {country} With  Test Day Milk Record', ['country' => $country->name]); ?>                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>