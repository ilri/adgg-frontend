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
<div class="mb-3">
    <div class="card card-body">
        <div class="col-md-12">
            <?php $idPrefix = 'fertility-g-filter-'; ?>
            <div class="chartFilters col-md-12 mb-4">
                <?= Html::beginForm(Url::to(array_merge(['load-chart'], Yii::$app->request->get())), 'post', ['class' => 'row justify-content-md-center chart-filter-form', 'id' => $idPrefix. 'form', 'data-name' => 'fertility']) ?>
                <?= $this->render('/data-viz/country/_location_filters', [
                    'filterOptions' => $filterOptions,
                    'idPrefix' => $idPrefix
                ]) ?>
                <div class="col-md-2">
                    <br>
                    <?= Select2::widget([
                        'name' => 'graph_type',
                        'value' => $filterOptions['graph_type'] ?? DataViz::GRAPH_LINE,
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
                <?= $this->render('/data-viz/country/_location_filters', [
                    'filterOptions' => $filterOptions,
                    'idPrefix' => $idPrefix
                ]) ?>
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
                        'name' => 'animal_type',
                        'value' => $filterOptions['animal_type'] ?? DataViz::ANIMAL_TYPE_CALF,
                        'data' => DataViz::animalTypeOptions('--Animal Type--') ,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'id' => $idPrefix . 'animal_type',
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
                        'value' => $filterOptions['graph_type'] ?? DataViz::GRAPH_LINE,
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
                <?= $this->render('/data-viz/country/_location_filters', [
                    'filterOptions' => $filterOptions,
                    'idPrefix' => $idPrefix
                ]) ?>
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
                        'value' => $filterOptions['graph_type'] ?? DataViz::GRAPH_LINE,
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
            <div class="col-md-12" id="avg_milk_yield"></div>
        </div>
    </div>
</div>