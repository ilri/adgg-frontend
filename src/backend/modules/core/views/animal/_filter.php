<?php

use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Choices;
use backend\modules\core\models\Farm;
use backend\modules\core\models\OrganizationRef;
use backend\modules\core\models\OrganizationRefUnits;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use yii\bootstrap4\Html;

/* @var $model \backend\modules\core\models\Animal */
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
                        <?= Html::label($model->getAttributeLabel('farm_type')) ?>
                        <?= Select2::widget([
                            'name' => 'farm_type',
                            'value' => $model->farm_id,
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_FARM_TYPE, false),
                            'options' => [
                                'placeholder' => "--All Farms--",
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
                                'data' => OrganizationRef::getListData(),
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
                                'data' => OrganizationRefUnits::getListData('id', 'name', false, ['country_id' => $model->country_id, 'level' => OrganizationRefUnits::LEVEL_REGION]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'region_id'),
                                    'placeholder' => "",
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['organization-ref-units/get-list', 'country_id' => 'idV', 'level' => OrganizationRefUnits::LEVEL_REGION]),
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
                                'data' => OrganizationRefUnits::getListData('id', 'name', false, ['parent_id' => $model->region_id, 'level' => OrganizationRefUnits::LEVEL_DISTRICT]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'district_id'),
                                    'placeholder' => "",
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['organization-ref-units/get-list', 'parent_id' => 'idV', 'level' => OrganizationRefUnits::LEVEL_DISTRICT]),
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
                                'data' => OrganizationRefUnits::getListData('id', 'name', false, ['parent_id' => $model->district_id, 'level' => OrganizationRefUnits::LEVEL_WARD]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'ward_id'),
                                    'placeholder' => "",
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['organization-ref-units/get-list', 'parent_id' => 'idV', 'level' => OrganizationRefUnits::LEVEL_WARD]),
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
                                'data' => OrganizationRefUnits::getListData('id', 'name', false, ['parent_id' => $model->ward_id, 'level' => OrganizationRefUnits::LEVEL_VILLAGE]),
                                'options' => [
                                    'id' => Html::getInputId($model, 'village_id'),
                                    'placeholder' => "",
                                    'class' => 'form-control select2',
                                    'data-url' => Url::to(['organization-ref-units/get-list', 'parent_id' => 'idV', 'level' => OrganizationRefUnits::LEVEL_VILLAGE]),
                                    'data-selected' => $model->village_id,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('animal_type')) ?>
                        <?= Select2::widget([
                            'name' => 'animal_type',
                            'value' => $model->animal_type,
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, false),
                            'options' => [
                                'placeholder' => "--All animals--",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('main_breed')) ?>
                        <?= Select2::widget([
                            'name' => 'main_breed',
                            'value' => $model->main_breed,
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, false),
                            'options' => [
                                'placeholder' => "--All breeds--",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('tag_id')) ?>
                        <?= Html::textInput('tag_id', $model->tag_id, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('name')) ?>
                        <?= Html::textInput('name', $model->name, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('sire_tag_id')) ?>
                        <?= Html::textInput('sire_tag_id', $model->sire_tag_id, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('dam_tag_id')) ?>
                        <?= Html::textInput('dam_tag_id', $model->dam_tag_id, ['class' => 'form-control']) ?>
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