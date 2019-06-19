<?php

use backend\modules\auth\Session;
use backend\modules\core\models\LookupList;
use backend\modules\core\models\Organization;
use backend\modules\core\models\OrganizationUnits;
use common\helpers\Lang;
use common\widgets\select2\Select2;
use yii\bootstrap4\Html;

/* @var $model \backend\modules\core\models\Client */
?>

<div class="accordion mb-5" id="accordion">
    <div class="card">
        <div class="card-header">
            <div class="card-title" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                 aria-controls="collapseOne">
                <i class="fas fa-chevron-right"></i> <?= Lang::t('Filters') ?>:
            </div>
        </div>
        <div id="collapseOne" class="collapse" data-parent="#accordion">
            <div class="card-body">
                <?= Html::beginForm(['index'], 'get', ['class' => '', 'id' => 'grid-filter-form', 'data-grid' => $model->getPjaxWidgetId()]) ?>
                <div class="form-row align-items-center">
                    <?php if (!Session::isOrganization()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('org_id')) ?>
                            <?= Select2::widget([
                                'name' => 'org_id',
                                'value' => $model->org_id,
                                'data' => Organization::getListData(),
                                'options' => [
                                    'placeholder' => "",
                                    'class' => 'form-control select2',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!Session::isOrganization() || Session::isCountryUser()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('region_id')) ?>
                            <?= Select2::widget([
                                'name' => 'region_id',
                                'value' => $model->region_id,
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['org_id' => $model->org_id, 'level' => OrganizationUnits::LEVEL_REGION]),
                                'options' => [
                                    'placeholder' => "",
                                    'class' => 'form-control select2',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!Session::isOrganization() || Session::isCountryUser() || Session::isRegionUser()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('district_id')) ?>
                            <?= Select2::widget([
                                'name' => 'district_id',
                                'value' => $model->district_id,
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['parent_id' => $model->region_id, 'level' => OrganizationUnits::LEVEL_DISTRICT]),
                                'options' => [
                                    'placeholder' => "",
                                    'class' => 'form-control select2',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!Session::isOrganization() || Session::isCountryUser() || Session::isRegionUser() || Session::isDistrictUser()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('ward_id')) ?>
                            <?= Select2::widget([
                                'name' => 'ward_id',
                                'value' => $model->ward_id,
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['parent_id' => $model->district_id, 'level' => OrganizationUnits::LEVEL_WARD]),
                                'options' => [
                                    'placeholder' => "",
                                    'class' => 'form-control select2',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!Session::isOrganization() || Session::isCountryUser() || Session::isRegionUser() || Session::isDistrictUser() || Session::isWardUser()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('village_id')) ?>
                            <?= Select2::widget([
                                'name' => 'village_id',
                                'value' => $model->village_id,
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['parent_id' => $model->ward_id, 'level' => OrganizationUnits::LEVEL_VILLAGE]),
                                'options' => [
                                    'placeholder' => "",
                                    'class' => 'form-control select2',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('name')) ?>
                        <?= Html::textInput('name', $model->name, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('code')) ?>
                        <?= Html::textInput('code', $model->code, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('phone')) ?>
                        <?= Html::textInput('phone', $model->phone, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('is_head')) ?>
                        <?= Select2::widget([
                            'name' => 'is_head',
                            'value' => $model->is_head,
                            'data' => \common\helpers\Utils::booleanOptions(),
                            'options' => [
                                'placeholder' => "",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('project')) ?>
                        <?= Select2::widget([
                            'name' => 'project',
                            'value' => $model->project,
                            'data' => LookupList::getProjectListData(),
                            'options' => [
                                'placeholder' => "",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('farm_type')) ?>
                        <?= Select2::widget([
                            'name' => 'farm_type',
                            'value' => $model->farm_type,
                            'data' => LookupList::getFarmTypeListData(),
                            'options' => [
                                'placeholder' => "",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('gender_code')) ?>
                        <?= Select2::widget([
                            'name' => 'gender_code',
                            'value' => $model->gender_code,
                            'data' => LookupList::getGenderListData(),
                            'options' => [
                                'placeholder' => "",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('is_active')) ?>
                        <?= Select2::widget([
                            'name' => 'is_active',
                            'value' => $model->is_active,
                            'data' => \common\helpers\Utils::booleanOptions(),
                            'options' => [
                                'placeholder' => "",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
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