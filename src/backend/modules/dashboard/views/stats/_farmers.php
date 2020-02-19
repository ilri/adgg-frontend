<?php

use backend\controllers\BackendController;
use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use backend\modules\core\models\CountryUnits;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\highchart\HighChart;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller BackendController */
/* @var $graphFilterOptions array */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>


    <div class="col-md-6">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="col-md-12 kt-iconbox kt-iconbox--active">
                <div id="chartContainer"></div>
                <?php
                // $this->render('graph/_widget', ['graphType' => HighChart::GRAPH_PIE, 'graphFilterOptions' => $graphFilterOptions])
                $condition = '';
                $params = [];
                list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
                $data = [];
                // get districts
                $districts = CountryUnits::getListData('id', 'name', '', ['country_id' => 10, 'level' => CountryUnits::LEVEL_REGION]);
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
        <!--end::Portlet-->
    </div>
<div class="col-md-6">
    <div class="row">
        <div class="col-md-6">
            <div class="kt-portlet">
                <div class="kt-iconbox kt-iconbox--active">
                    <div class="kt-iconbox__icon mb-0">
                        <div class="kt-iconbox__icon-bg"></div>
                        <span><?= number_format(Farm::getCount(['country_id' => $graphFilterOptions['country_id']])) ?></span>
                    </div>
                    <div class="kt-iconbox__title">ADGG Number of Farmers</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="kt-portlet">
                <div class="kt-iconbox kt-iconbox--active">
                    <div class="kt-iconbox__icon mb-0">
                        <div class="kt-iconbox__icon-bg"></div>
                        <span><?= number_format(Animal::getCount(['country_id' => $graphFilterOptions['country_id']])) ?></span>
                    </div>
                    <div class="kt-iconbox__title">ADGG Number of Animals</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="kt-portlet">
                <div class="kt-iconbox kt-iconbox--active">
                    <div class="kt-iconbox__icon mb-0">
                        <div class="kt-iconbox__icon-bg"></div>
                        <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 1])->count()) ?></span>
                    </div>
                    <div class="kt-iconbox__title">ADGG Male Household headed Farmers</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="kt-portlet">
                <div class="kt-iconbox kt-iconbox--active">
                    <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 2])->count()) ?></span>
                        </div>
                        <div class="kt-iconbox__title">ADGG Female Household headed Farmers</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => [1,2]])->count()) ?></span>
                        </div>
                        <div class="kt-iconbox__title">Households headed by both male and female members</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

