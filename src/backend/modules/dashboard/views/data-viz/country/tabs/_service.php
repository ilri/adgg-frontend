<?php

use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use backend\modules\dashboard\models\DataViz;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $controller backend\controllers\BackendController */
/* @var $filterOptions array */

$controller = Yii::$app->controller;
$tabType = Yii::$app->request->get('tab_type', null);
?>
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
                    <?= Select2::widget([
                        'name' => 'graph_type',
                        'value' => $filterOptions['graph_type'] ?? DataViz::GRAPH_COLUMN,
                        'data' => DataViz::graphTypeOptions() ,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'id' => $idPrefix . 'graph_type',
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
                    <?= Select2::widget([
                        'name' => 'graph_type',
                        'value' => $filterOptions['graph_type'] ?? DataViz::GRAPH_COLUMN,
                        'data' => DataViz::graphTypeOptions(false, [DataViz::GRAPH_PIE]) ,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'id' => $idPrefix . 'graph_type',
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
