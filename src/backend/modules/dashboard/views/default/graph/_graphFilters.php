<?php

use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Choices;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use backend\modules\reports\Constants;
use common\widgets\highchart\HighChart;
use common\widgets\select2\Select2;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $filterOptions array */
$idPrefix = 'animal-g-filter-';
?>
<div class="row">
    <div class="col-md-2">
        <?= Select2::widget([
            'name' => 'country_id',
            'value' => $filterOptions['country_id'] ?? null,
            'data' => Country::getListData('id', 'name', '--All Countries--'),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'country_id',
                'class' => 'form-control parent-depdropdown',
                'data-child-selectors' => [
                    '#' . $idPrefix . 'region_id',
                ],
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-md-2">
        <?= Select2::widget([
            'name' => 'region_id',
            'value' => $filterOptions['region_id'] ?? null,
            'data' => ['' => '--All Regions--'],
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'region_id',
                'class' => 'form-control parent-depdropdown',
                'data-url' => Url::to(['/core/OrganizationRef-units/get-list', 'country_id' => 'idV', 'level' => CountryUnits::LEVEL_REGION, 'placeholder' => '--All Regions--']),
                'data-child-selectors' => [
                    '#' . $idPrefix . 'district_id',
                ],
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-md-2">
        <?= Select2::widget([
            'name' => 'district_id',
            'value' => $filterOptions['district_id'] ?? null,
            'data' => ['' => '--All Districts--'],
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'district_id',
                'class' => 'form-control parent-depdropdown',
                'data-url' => Url::to(['/core/OrganizationRef-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_DISTRICT, 'placeholder' => '--All Districts--']),
                'data-child-selectors' => [
                    '#' . $idPrefix . 'ward_id',
                ],
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-md-2">
        <?= Select2::widget([
            'name' => 'ward_id',
            'value' => $filterOptions['ward_id'] ?? null,
            'data' => ['' => '--All Wards--'],
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'ward_id',
                'class' => 'form-control parent-depdropdown',
                'data-url' => Url::to(['/core/OrganizationRef-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_WARD, 'placeholder' => '--All Wards--']),
                'data-child-selectors' => [
                    '#' . $idPrefix . 'village_id',
                ],
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-md-2">
        <?= Select2::widget([
            'name' => 'village_id',
            'value' => $filterOptions['village_id'] ?? null,
            'data' => ['' => '--All Villages--'],
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'village_id',
                'data-url' => Url::to(['/core/OrganizationRef-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_VILLAGE, 'placeholder' => '--All Villages--']),
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-sm-2">{date_range}</div>
</div>
<br/>
<div class="row">
    <div class="col-sm-2">
        <?= Select2::widget([
            'name' => 'groupBy',
            'value' => $filterOptions['groupBy'] ?? null,
            'data' => Constants::animalGraphGroupByOptions(),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'groupBy',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-md-2">
        <?= Select2::widget([
            'name' => 'animal_type',
            'value' => $filterOptions['animal_type'] ?? null,
            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, '--All animals-'),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'animal_type',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-md-2">
        <?= Select2::widget([
            'name' => 'main_breed',
            'value' => $filterOptions['main_breed'] ?? null,
            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, '--All breeds--'),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'main_breed',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-sm-2">
        <?= Select2::widget([
            'name' => HighChart::GET_PARAM_GRAPH_TYPE,
            'value' => $graphType,
            'data' => HighChart::graphTypeOptions(false, []),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'graph-type',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-sm-1">{button}</div>
</div>