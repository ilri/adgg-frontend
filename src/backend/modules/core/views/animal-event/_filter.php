<?php

use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Client;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use yii\bootstrap4\Html;

/* @var $model AnimalEvent */
?>

<div class="accordion mb-5" id="accordion">
    <div class="card">
        <div class="card-header">
            <div class="card-title" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                 aria-controls="collapseOne">
                <i class="fas fa-chevron-down"></i> <?= Lang::t('Filters') ?>:
            </div>
        </div>
        <div id="collapseOne" class="collapse show" data-parent="#accordion">
            <div class="card-body">
                <?= Html::beginForm(['index'], 'get', ['class' => '', 'id' => 'grid-filter-form', 'data-grid' => $model->getPjaxWidgetId()]) ?>
                <div class="form-row align-items-center">
                    <div class="col-lg-6">
                        <?= Html::label($model->getAttributeLabel('field_agent_id')) ?>
                        <?= Select2::widget([
                            'name' => 'field_agent_id',
                            'value' => $model->field_agent_id,
                            'data' => Client::getListData(),
                            'options' => [
                                'placeholder' => '[select one field agent]',
                                'class' => 'form-control select2 parent-depdropdown',
                                'data-child-selectors' => [
                                    '#' . Html::getInputId($model, 'name'),
                                ],
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>

                    </div>>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('event_type')) ?>
                        <?= Select2::widget([
                            'name' => 'event_type',
                            'value' => $model->event_type,
                            'data' => AnimalEvent::eventTypeOptions(),
                            'options' => [
                                'placeholder' => "--All Events--",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>

                    <?php if ($model->showCountryField()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('country_id')) ?>
                            <?= Select2::widget([
                                'name' => 'country_id',
                                'value' => $model->country_id,
                                'data' => Country::getListData(),
                                'options' => [
                                    'placeholder' => '[select one]',
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'region_id'),
                                    ],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->showRegionField()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('region_id')) ?>
                            <?= Select2::widget([
                                'name' => 'region_id',
                                'value' => $model->region_id,
                                'data' => CountryUnits::getListData('id', 'name', false, ['country_id' => $model->country_id, 'level' => CountryUnits::LEVEL_REGION]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'region_id'),
                                    'placeholder' => '[select one]',
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['country-units/get-list', 'country_id' => 'idV', 'level' => CountryUnits::LEVEL_REGION, 'placeholder' => true]),
                                    'data-selected' => $model->region_id,
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'district_id'),
                                    ],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->showDistrictField()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('district_id')) ?>
                            <?= Select2::widget([
                                'name' => 'district_id',
                                'value' => $model->district_id,
                                'data' => CountryUnits::getListData('id', 'name', false, ['parent_id' => $model->region_id, 'level' => CountryUnits::LEVEL_DISTRICT]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'district_id'),
                                    'placeholder' => '[select one]',
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['country-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_DISTRICT, 'placeholder' => true]),
                                    'data-selected' => $model->district_id,
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'ward_id'),
                                    ],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->showWardField()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('ward_id')) ?>
                            <?= Select2::widget([
                                'name' => 'ward_id',
                                'value' => $model->ward_id,
                                'data' => CountryUnits::getListData('id', 'name', false, ['parent_id' => $model->district_id, 'level' => CountryUnits::LEVEL_WARD]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'ward_id'),
                                    'placeholder' => '[select one]',
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['country-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_WARD, 'placeholder' => true]),
                                    'data-selected' => $model->ward_id,
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'village_id'),
                                    ],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->showVillageField()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('village_id')) ?>
                            <?= Select2::widget([
                                'name' => 'village_id',
                                'value' => $model->village_id,
                                'data' => CountryUnits::getListData('id', 'name', false, ['parent_id' => $model->ward_id, 'level' => CountryUnits::LEVEL_VILLAGE]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'village_id'),
                                    'placeholder' => '[select one]',
                                    'class' => 'form-control select2',
                                    'data-url' => Url::to(['country-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_VILLAGE, 'placeholder' => true]),
                                    'data-selected' => $model->village_id,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-1">
                        <?= Html::label($model->getAttributeLabel('From')) ?>
                        <?= Html::textInput('from', $model->_dateFilterFrom, ['class' => 'form-control show-datepicker', 'placeholder' => 'From']) ?>
                    </div>
                    <div class="col-lg-1">
                        <?= Html::label($model->getAttributeLabel('To')) ?>
                        <?= Html::textInput('to', $model->_dateFilterTo, ['class' => 'form-control show-datepicker', 'placeholder' => 'To']) ?>
                    </div>
                    <div class="col-lg-2">
                        <br>
                        <button class="btn btn-primary pull-left" type="submit"><?= Lang::t('Go') ?></button>
                        &nbsp;
                        <button class="btn btn-default" type="reset"
                                onclick="$('select.select2').val('').trigger('change');"><?= Lang::t('Reset') ?></button>
                    </div>
                </div>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>