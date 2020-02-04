<?php

use backend\controllers\BackendController;
use backend\modules\core\models\Choices;
use backend\modules\core\models\Farm;
use backend\modules\core\models\Organization;
use backend\modules\core\models\OrganizationUnits;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller BackendController */
/* @var $graphFilterOptions array */
/* @var $country Organization */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
<h3><?= Lang::t('Farms Registered in {country}', ['country' => $country->name]); ?></h3>
<hr>
<div class="row">
    <div class="col-md-6">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="col-md-12 kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__title">
                    <?= Lang::t(' Registered Farms Grouped by Regions in {country}', ['country' => $country->name]); ?>
                </div>
                <div id="chartContainer"></div>
                <!--                $this->render('graph/_widget', ['graphType' => HighChart::GRAPH_PIE, 'graphFilterOptions' => $graphFilterOptions])
                --> <?php
                $condition = '';
                $params = [];
                list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
                $data = [];
                // get regions
                $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
                foreach ($regions as $id => $label) {
                    list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

                    $count = Farm::find()->andWhere($newcondition, $newparams)
                        ->andWhere(['org_id' => $country->id])
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
                $graphOptions = [];
                $containerId = 'chartContainer';
                $this->registerJs("MyApp.modules.dashboard.piechart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

                ?>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="col-md-12 kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__title">
                    <?= Lang::t(' Registered Farms Grouped by Farm Type in {country}', ['country' => $country->name]); ?>
                </div>
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
                    $count = Farm::find()->andWhere($newcondition, $newparams)
                        ->andWhere(['org_id' => $country->id])
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
                    <span><?= number_format(Farm::getCount(['org_id' => $country->id])) ?></span>
                </div>
                <div class="kt-iconbox__title">
                    <?= Lang::t('Number Of Farms in {country}', ['country' => $country->name]); ?>                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 1])->andWhere(['org_id' => $country->id])->count()) ?></span>
                </div>
                <div class="kt-iconbox__title">
                    <?= Lang::t('Male Household headed Farmers in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 2])->andWhere(['org_id' => $country->id])->count()) ?></span>
                </div>
                <div class="kt-iconbox__title"><?= Lang::t('Female Household headed Farmers in {country}', ['country' => $country->name]); ?></div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => [1, 2]])->andWhere(['org_id' => $country->id])->count()) ?></span>
                </div>
                <div class="kt-iconbox__title">
                    <?= Lang::t('Households headed by both male and female members in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
