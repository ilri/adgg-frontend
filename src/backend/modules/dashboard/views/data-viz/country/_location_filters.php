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
/* @var $idPrefix string */
$controller = Yii::$app->controller;
$tabType = Yii::$app->request->get('tab_type', null);
?>
<div class="col-md-2">
    <br>
    <?= Select2::widget([
        'name' => 'region_id',
        'value' => $filterOptions['region_id'] ?? null,
        'data' => CountryUnits::getListData('id', 'name', '-- All Regions --', ['country_id' => $filterOptions['country_id'], 'level' => CountryUnits::LEVEL_REGION]),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'id' => $idPrefix . 'region_id',
            'multiple' => true,
            'class' => 'form-control select2 parent-depdropdown',
            'data-url' => Url::to(['/core/country-units/get-list', 'country_id' => 'idV', 'level' => CountryUnits::LEVEL_REGION, 'placeholder' => '-- All Regions --']),
            'data-child-selectors' => [
                '#' . $idPrefix .'district_id',
            ],
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]); ?>
</div>
<div class="col-md-2">
    <br>
    <?= Select2::widget([
        'name' => 'district_id',
        'value' => $filterOptions['district_id'] ?? null,
        'data' => CountryUnits::getListData('id', 'name', '-- All Districts --', ['parent_id' => $filterOptions['region_id'] ?? null, 'level' => CountryUnits::LEVEL_DISTRICT]),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'id' => $idPrefix . 'district_id',
            'multiple' => true,
            'class' => 'form-control select2 parent-depdropdown',
            'data-url' => Url::to(['/core/country-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_DISTRICT, 'placeholder' => '-- All Districts --']),
            'data-child-selectors' => [
                '#' . $idPrefix .'ward_id',
            ],
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]); ?>
</div>
<div class="col-md-2">
    <br>
    <?= Select2::widget([
        'name' => 'ward_id',
        'value' => $filterOptions['ward_id'] ?? null,
        'data' => CountryUnits::getListData('id', 'name', '-- All Wards --', ['parent_id' => $filterOptions['ward_id'] ?? null, 'level' => CountryUnits::LEVEL_WARD]),
        'options' => [
            'id' => $idPrefix . 'ward_id',
            'multiple' => true,
            'class' => 'form-control select2 parent-depdropdown',
            'data-url' => Url::to(['/core/country-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_WARD, 'placeholder' => '-- All Wards --']),
            'data-selected' => $filterOptions['ward_id'] ?? null,
            'data-child-selectors' => [
                '#' . $idPrefix .'village_id',
            ],
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
</div>
<div class="col-md-2">
    <br>
    <?= Select2::widget([
        'name' => 'village_id',
        'value' => $filterOptions['village_id'] ?? null,
        'data' => CountryUnits::getListData('id', 'name', '-- All Villages --', ['parent_id' => $filterOptions['village_id'] ?? null, 'level' => CountryUnits::LEVEL_VILLAGE]),
        'options' => [
            'id' => $idPrefix . 'village_id',
            'multiple' => true,
            'class' => 'form-control select2',
            'data-url' => Url::to(['/core/country-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_VILLAGE, 'placeholder' => '-- All Villages --']),
            'data-selected' => $filterOptions['village_id'] ?? null,
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

</div>