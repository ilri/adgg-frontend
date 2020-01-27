<?php

use backend\controllers\BackendController;
use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
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
<h3><?= Lang::t('Animals Registered in {country}', ['country' => $country->name]); ?></h3>
<hr>
<div class="row">
    <div class="col-md-6">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="col-md-12 kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__title">
                    <?= Lang::t('Registered Animals Grouped by Regions in {country}', ['country' => $country->name]); ?>
                </div>
                <div id="chartContainer"></div>
                <?php
                $condition = '';
                $params = [];
                list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
                $data = [];
                // get regions
                $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
                foreach ($regions as $id => $label) {
                    list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

                    $count = Animal::find()->andWhere($newcondition, $newparams)->andWhere(['org_id' => $country->id])->count();
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
                    <?= Lang::t('Registered Animals Grouped by Breeds in {country}', ['country' => $country->name]); ?>
                </div>
                <div id="chartContainer2"></div>
                <!--                $this->render('graph/_widget', ['graphType' => HighChart::GRAPH_PIE, 'graphFilterOptions' => $graphFilterOptions])
                --> <?php
                $condition = '';
                $params = [];
                list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
                $data = [];
                // get breeds
                $breeds = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
                foreach ($breeds as $id => $label) {
                    list($newcondition, $newparams) = DbUtils::appendCondition('main_breed', $id, $condition, $params);

                    $count = Animal::find()->andWhere($newcondition, $newparams)
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
        </div>

        <!--end::Portlet-->
    </div>
    <div class="col-md-6">
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Animal::getCount(['org_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_COW])) ?></span>
                </div>
                <div class="kt-iconbox__title">
                    <?= Lang::t('ADGG Number of Cows in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Animal::getCount(['org_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_HEIFER])) ?></span>
                </div>
                <div class="kt-iconbox__title"><?= Lang::t('ADGG Number of Heifers in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Animal::getCount(['org_id' => $country->id, 'animal_type' => Animal::ANIMAL_TYPE_BULL])) ?></span>
                </div>
                <div class="kt-iconbox__title"><?= Lang::t('ADGG Number of Bulls in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Animal::getCount(['org_id' => $country->id, 'animal_type' => [Animal::ANIMAL_TYPE_MALE_CALF]])) ?></span>
                </div>
                <div class="kt-iconbox__title"><?= Lang::t('ADGG Number of Male Calves in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
        <div class="kt-portlet">
            <div class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon mb-0">
                    <div class="kt-iconbox__icon-bg"></div>
                    <span><?= number_format(Animal::getCount(['org_id' => $country->id, 'animal_type' => [Animal::ANIMAL_TYPE_FEMALE_CALF]])) ?></span>
                </div>
                <div class="kt-iconbox__title"><?= Lang::t('ADGG Number of Female Calves in {country}', ['country' => $country->name]); ?>
                </div>
            </div>
        </div>
    </div>
</div>