<?php

use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Organization;
use backend\modules\core\models\OrganizationUnits;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use yii\bootstrap4\Html;

/* @var $model \backend\modules\core\models\AnimalEvent */
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
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('animal_id')) ?>
                        <?= Select2::widget([
                            'name' => 'farm_id',
                            'value' => $model->animal_id,
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, false),
                            'options' => [
                                'placeholder' => "--All Animals--",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
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
                            <?= Html::label($model->getAttributeLabel('org_id')) ?>
                            <?= Select2::widget([
                                'name' => 'org_id',
                                'value' => $model->org_id,
                                'data' => Organization::getListData(),
                                'options' => [
                                    'placeholder' => "",
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
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['org_id' => $model->org_id, 'level' => OrganizationUnits::LEVEL_REGION]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'region_id'),
                                    'placeholder' => "",
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['organization-units/get-list', 'org_id' => 'idV', 'level' => OrganizationUnits::LEVEL_REGION]),
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
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['parent_id' => $model->region_id, 'level' => OrganizationUnits::LEVEL_DISTRICT]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'district_id'),
                                    'placeholder' => "",
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['organization-units/get-list', 'parent_id' => 'idV', 'level' => OrganizationUnits::LEVEL_DISTRICT]),
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
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['parent_id' => $model->district_id, 'level' => OrganizationUnits::LEVEL_WARD]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'ward_id'),
                                    'placeholder' => "",
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['organization-units/get-list', 'parent_id' => 'idV', 'level' => OrganizationUnits::LEVEL_WARD]),
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
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['parent_id' => $model->ward_id, 'level' => OrganizationUnits::LEVEL_VILLAGE]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'village_id'),
                                    'placeholder' => "",
                                    'class' => 'form-control select2',
                                    'data-url' => Url::to(['organization-units/get-list', 'parent_id' => 'idV', 'level' => OrganizationUnits::LEVEL_VILLAGE]),
                                    'data-selected' => $model->village_id,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-2">
                        <?= Html::label('Animal Tag ID') ?>
                        <?= Html::textInput('animal_tag_id', Yii::$app->request->get('animal_tag_id'), ['class' => 'form-control']) ?>
                    </div>
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