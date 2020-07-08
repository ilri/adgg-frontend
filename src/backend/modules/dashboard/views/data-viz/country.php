<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Farm;
use backend\modules\core\models\MilkingEvent;
use common\helpers\ArrayHelper;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $filterOptions array */
$controller = Yii::$app->controller;
$this->title = Lang::t('{country} Dashboard', ['country' => Country::getScalar('name', ['id' => $filterOptions['country_id']])]);
$this->params['breadcrumbs'] = [
    $this->title,
];

?>
<?= $this->render('country/_tab', []) ?>
<div class="tab-content">
    <div class="tab-pane active" id="performance" role="tabpanel">
        <div class="mb-3">
            <div class="card card-body">
                <div class="col-md-12">
                    <?php $idPrefix = 'fertility-g-filter-'; ?>
                    <div class="chartFilters col-md-12 mb-4">
                        <?= Html::beginForm(Url::to(array_merge(['load-chart'], Yii::$app->request->get())), 'post', ['class' => 'row justify-content-md-center chart-filter-form', 'id' => $idPrefix. 'form', 'data-name' => 'fertility']) ?>
                        <div class="col-md-2">
                            <br>
                            <?= Select2::widget([
                                'name' => 'region_id',
                                'value' => $filterOptions['region_id'] ?? null,
                                'data' => CountryUnits::getListData('id', 'name', '-- All Regions --', ['country_id' => $filterOptions['country_id'], 'level' => CountryUnits::LEVEL_REGION]),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'id' => $idPrefix . 'region_id',
                                    'class' => 'form-control parent-depdropdown',
                                    'data-url' => Url::to(['/core/country-units/get-list', 'country_id' => 'idV', 'level' => CountryUnits::LEVEL_REGION, 'placeholder' => '-- All Regions --']),
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]); ?>
                        </div>
                        <div class="col-md-2">
                            <br>
                            <button class="btn btn-primary pull-left" type="submit"><?= Lang::t('Go') ?></button>
                            &nbsp;
                            <button class="btn btn-default" type="reset"
                                    onclick="$('select.select2').val('').trigger('change');"><?= Lang::t('Reset') ?></button>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                    <div class="col-md-12" id="fertility"></div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <div class="card card-body">
                <div class="col-md-12">
                    <?php $idPrefix = 'avg-body-weight-g-filter-'; ?>
                    <div class="chartFilters col-md-12 mb-4">
                        <?= Html::beginForm(Url::to(array_merge(['load-chart'], Yii::$app->request->get())), 'post', ['class' => 'row justify-content-md-center chart-filter-form', 'id' => $idPrefix. 'form', 'data-name' => 'avg_body_weight']) ?>
                        <div class="col-md-2">
                            <br>
                            <?= Select2::widget([
                                'name' => 'region_id',
                                'value' => $filterOptions['region_id'] ?? null,
                                'data' => CountryUnits::getListData('id', 'name', '-- All Regions --', ['country_id' => $filterOptions['country_id'], 'level' => CountryUnits::LEVEL_REGION]),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'id' => $idPrefix . 'region_id',
                                    'class' => 'form-control parent-depdropdown',
                                    'data-url' => Url::to(['/core/country-units/get-list', 'country_id' => 'idV', 'level' => CountryUnits::LEVEL_REGION, 'placeholder' => '-- All Regions --']),
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]); ?>
                        </div>
                        <div class="col-md-2">
                            <br>
                            <?= Select2::widget([
                                'name' => 'year',
                                'value' => $filterOptions['year'] ?? null,
                                'data' => CountriesDashboardStats::rangeYearsDropdown() ,
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'id' => $idPrefix . 'year',
                                    'class' => 'form-control',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]); ?>
                        </div>
                        <div class="col-md-2">
                            <br>
                            <button class="btn btn-primary pull-left" type="submit"><?= Lang::t('Go') ?></button>
                            &nbsp;
                            <button class="btn btn-default" type="reset"
                                    onclick="$('select.select2').val('').trigger('change');"><?= Lang::t('Reset') ?></button>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                    <div class="col-md-12" id="avg_body_weight"></div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <div class="card card-body">
                <div class="col-md-12">
                    <?php $idPrefix = 'avg_milk_yield-g-filter-'; ?>
                    <div class="chartFilters col-md-12 mb-4">
                    <?= Html::beginForm(Url::to(array_merge(['load-chart'], Yii::$app->request->get())), 'post', ['class' => 'row justify-content-md-center chart-filter-form', 'id' => $idPrefix. 'form', 'data-name' => 'avg_milk_yield']) ?>
                    <div class="col-md-2">
                        <br>
                        <?= Select2::widget([
                            'name' => 'region_id',
                            'value' => $filterOptions['region_id'] ?? null,
                            'data' => CountryUnits::getListData('id', 'name', '-- All Regions --', ['country_id' => $filterOptions['country_id'], 'level' => CountryUnits::LEVEL_REGION]),
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => [
                                'id' => $idPrefix . 'region_id',
                                'class' => 'form-control',
                                'data-url' => Url::to(['/core/country-units/get-list', 'country_id' => 'idV', 'level' => CountryUnits::LEVEL_REGION, 'placeholder' => '-- All Regions --']),
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]); ?>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <?= Select2::widget([
                            'name' => 'year',
                            'value' => $filterOptions['year'] ?? null,
                            'data' => CountriesDashboardStats::rangeYearsDropdown() ,
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => [
                                'id' => $idPrefix . 'year',
                                'class' => 'form-control',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]); ?>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <button class="btn btn-primary pull-left" type="submit"><?= Lang::t('Go') ?></button>
                        &nbsp;
                        <button class="btn btn-default" type="reset"
                                onclick="$('select.select2').val('').trigger('change');"><?= Lang::t('Reset') ?></button>
                    </div>
                    <?= Html::endForm() ?>
                </div>
                    <div class="col-md-12" id="avg_milk_yield"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="insemination" role="tabpanel">
        <div class="row mb-3">
            <div class="card card-body">
                <div class="col-md-12">
                    <?php $idPrefix = 'avg_ai_preg-g-filter-'; ?>
                    <div class="chartFilters col-md-12 mb-4">
                        <?= Html::beginForm(Url::to(array_merge(['load-chart'], Yii::$app->request->get())), 'post', ['class' => 'row justify-content-md-center chart-filter-form', 'id' => $idPrefix. 'form', 'data-name' => 'avg_ai_preg']) ?>
                        <div class="col-md-2">
                            <br>
                            <?= Select2::widget([
                                'name' => 'region_id',
                                'value' => $filterOptions['region_id'] ?? null,
                                'data' => CountryUnits::getListData('id', 'name', '-- All Regions --', ['country_id' => $filterOptions['country_id'], 'level' => CountryUnits::LEVEL_REGION]),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'id' => $idPrefix . 'region_id',
                                    'class' => 'form-control parent-depdropdown',
                                    'data-url' => Url::to(['/core/country-units/get-list', 'country_id' => 'idV', 'level' => CountryUnits::LEVEL_REGION, 'placeholder' => '-- All Regions --']),
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]); ?>
                        </div>
                        <div class="col-md-2">
                            <br>
                            <button class="btn btn-primary pull-left" type="submit"><?= Lang::t('Go') ?></button>
                            &nbsp;
                            <button class="btn btn-default" type="reset"
                                    onclick="$('select.select2').val('').trigger('change');"><?= Lang::t('Reset') ?></button>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                    <div class="col-md-12" id="avg_ai_preg"></div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="card card-body">
                <div class="col-md-12">
                    <?php $idPrefix = 'ai_per_breed-g-filter-'; ?>
                    <div class="chartFilters col-md-12 mb-4">
                        <?= Html::beginForm(Url::to(array_merge(['load-chart'], Yii::$app->request->get())), 'post', ['class' => 'row justify-content-md-center chart-filter-form', 'id' => $idPrefix. 'form', 'data-name' => 'ai_per_breed']) ?>
                        <div class="col-md-2">
                            <br>
                            <?= Select2::widget([
                                'name' => 'region_id',
                                'value' => $filterOptions['region_id'] ?? null,
                                'data' => CountryUnits::getListData('id', 'name', '-- All Regions --', ['country_id' => $filterOptions['country_id'], 'level' => CountryUnits::LEVEL_REGION]),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'id' => $idPrefix . 'region_id',
                                    'class' => 'form-control',
                                    'data-url' => Url::to(['/core/country-units/get-list', 'country_id' => 'idV', 'level' => CountryUnits::LEVEL_REGION, 'placeholder' => '-- All Regions --']),
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]); ?>
                        </div>
                        <div class="col-md-2">
                            <br>
                            <?= Select2::widget([
                                'name' => 'year',
                                'value' => $filterOptions['year'] ?? null,
                                'data' => CountriesDashboardStats::rangeYearsDropdown() ,
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'id' => $idPrefix . 'year',
                                    'class' => 'form-control',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]); ?>
                        </div>
                        <div class="col-md-2">
                            <br>
                            <button class="btn btn-primary pull-left" type="submit"><?= Lang::t('Go') ?></button>
                            &nbsp;
                            <button class="btn btn-default" type="reset"
                                    onclick="$('select.select2').val('').trigger('change');"><?= Lang::t('Reset') ?></button>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                    <div class="col-md-12" id="ai_per_breed"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$options = [
    'ajaxAction' => Url::to(array_merge(['data-viz/load-chart', 'is_country' => true], $filterOptions)),
    'ajaxCharts' => [
        [
            'name' => 'fertility',
            'renderContainer' => '#fertility'
        ],
        [
            'name' => 'avg_body_weight',
            'renderContainer' => '#avg_body_weight'
        ],
        [
            'name' => 'avg_milk_yield',
            'renderContainer' => '#avg_milk_yield'
        ],
        [
            'name' => 'avg_ai_preg',
            'renderContainer' => '#avg_ai_preg'
        ],
        [
            'name' => 'ai_per_breed',
            'renderContainer' => '#ai_per_breed'
        ],

    ]
];
$this->registerJs("MyApp.modules.dashboard.dataviz(" . Json::encode($options) . ");");
?>
