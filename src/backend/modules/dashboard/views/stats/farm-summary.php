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
<h3><?= Lang::t('Farms Registered ') ?></h3>
<hr>
<div class="row">
    <div class="col-md-6">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="col-md-12 kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__title">Registered Farms Grouped by Regions</div>
                <div id="chartContainer"></div>
                <!--                $this->render('graph/_widget', ['graphType' => HighChart::GRAPH_PIE, 'graphFilterOptions' => $graphFilterOptions])
                --> <?php
                $condition = '';
                $params = [];
                list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
                $data = [];
                // get districts
                $districts = OrganizationUnits::getListData('id', 'name', '', ['org_id' => 10, 'level' => OrganizationUnits::LEVEL_REGION]);
                foreach ($districts as $id => $label) {
                    list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

                    $count = Farm::find()->andWhere($newcondition, $newparams)->count();
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
                $graphOptions = [];
                $containerId = 'chartContainer';
                $this->registerJs("MyApp.modules.dashboard.piechart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

                ?>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="col-md-12 kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__title">Registered Farms Grouped by Farm Type</div>
                <div id="chartContainer2"></div>
                <!--                $this->render('graph/_widget', ['graphType' => HighChart::GRAPH_PIE, 'graphFilterOptions' => $graphFilterOptions])
                --> <?php
                $condition = '';
                $params = [];
                list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
                $data = [];
                // get farm types
                $farmTypes = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_FARM_TYPE);
                //print_r($farmTypes);
                foreach ($farmTypes as $type => $label) {
                    list($newcondition, $newparams) = DbUtils::appendCondition('farm_type', $type, $condition, $params);

                    // fetch count for each district
                    //print_r(Farm::find()->andWhere($newcondition, $newparams)->createCommand()->rawSql);
                    $count = Farm::find()->andWhere($newcondition, $newparams)->count();
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
                $graphOptions = [];
                $containerId = 'chartContainer2';
                $this->registerJs("MyApp.modules.dashboard.piechart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

                ?>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <div class="col-md-6">
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Farm::getCount(['org_id' => $graphFilterOptions['org_id']])) ?></span>
                </div>
                <div class="kt-iconbox__title">ADGG Number of Farmers</div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Animal::getCount(['org_id' => $graphFilterOptions['org_id']])) ?></span>
                </div>
                <div class="kt-iconbox__title">ADGG Number of Animals</div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 1])->count()) ?></span>
                </div>
                <div class="kt-iconbox__title">ADGG Male Household headed Farmers</div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 2])->count()) ?></span>
                </div>
                <div class="kt-iconbox__title">ADGG Female Household headed Farmers</div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => [1, 2]])->count()) ?></span>
                </div>
                <div class="kt-iconbox__title">Households headed by both male and female members</div>
            </div>
        </div>
    </div>
</div>
