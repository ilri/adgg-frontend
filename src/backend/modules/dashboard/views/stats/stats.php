<?php


use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use backend\modules\core\models\OrganizationRefUnits;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\helpers\Url;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $searchModel \backend\modules\core\models\Animal */
/* @var $graphFilterOptions array */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
?>
<div class="row">
    <div class="col-md-2">
        <?= $this->render('details'); ?>
    </div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-6">
                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="col-md-12 kt-iconbox kt-iconbox--active">
                        <div id="chartContainer"></div>
                        <!--                $this->render('graph/_widget', ['graphType' => HighChart::GRAPH_PIE, 'graphFilterOptions' => $graphFilterOptions])
                        --> <?php
                        $condition = '';
                        $params = [];
                        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
                        $data = [];
                        // get districts
                        $districts = OrganizationRefUnits::getListData('id', 'name', '', ['country_id' => 10, 'level' => OrganizationRefUnits::LEVEL_REGION]);
                        foreach ($districts as $id => $label) {
                            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

                            // fetch count for each district
                            //print_r(Farm::find()->andWhere($newcondition, $newparams)->createCommand()->rawSql);
                            $count = Farm::find()->andWhere($newcondition, $newparams)->count();
                            $data[] = [
                                'name' => $label,
                                'y' => floatval(number_format($count, 2, '.', '')),
                            ];
                        };
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
                <!--begin::Portlet-->
                <div class="row">
                    <div class="col-xl-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">Vertical Bar Chart</h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <canvas id="kt_chartjs_1" style="height: 350px; display: block; width: 526px;"
                                        width="657" height="375" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>
                        <!--end::Portlet-->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('_grid', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>

</div>
