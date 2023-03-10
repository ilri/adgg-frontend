<?php

use backend\modules\auth\models\Users;
use backend\modules\core\models\Country;
use backend\modules\core\models\Farm;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Organization;
use backend\modules\core\models\Client;
use common\forms\ActiveField;
use common\helpers\DateUtils;
use common\widgets\select2\Select2;
use yii\bootstrap\Html;
use common\helpers\Url;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \backend\modules\core\models\AnimalHerd */
/* @var $form ActiveForm */
?>
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title"><?= Html::encode($this->title) ?></h3>
        </div>
    </div>
    <?php
    $form = ActiveForm::begin([
        'id' => 'herd-form',
        'layout' => 'horizontal',
        'options' => ['class' => 'kt-form kt-form--label-right'],
        'fieldClass' => ActiveField::class,
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-md-3 col-form-label',
                'offset' => 'offset-md-3',
                'wrapper' => 'col-md-9',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]);
    ?>
    <div class="kt-portlet__body">
        <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']); ?>
        <div class="kt-section kt-section--first">
            <div class="kt-section__body">
                <h3 class="kt-section__title kt-section__title-lg"><?= Lang::t('Herd details') ?></h3>
                <div class="row">
                    <?php if ($model->showCountryField()): ?>
                        <div class="col-md-4">
                            <?= $form->field($model, 'country_id')->widget(Select2::class, [
                                'data' => Country::getListData(),
                                'options' => [
                                    'class' => 'form-control parent-depdropdown',
                                    'placeholder' => '[select one]',
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'region_id'),
                                    ],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->showRegionField()): ?>
                        <div class="col-md-4">
                            <?= $form->field($model, 'region_id')->widget(Select2::class, [
                                'data' => CountryUnits::getListData('id', 'name', false, ['country_id' => $model->country_id, 'level' => CountryUnits::LEVEL_REGION]),
                                'options' => [
                                    'class' => 'form-control parent-depdropdown',
                                    'placeholder' => '[select one]',
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'district_id'),
                                    ],
                                    'data-url' => Url::to(['OrganizationRef-units/get-list', 'country_id' => 'idV', 'level' => CountryUnits::LEVEL_REGION, 'placeholder' => true]),
                                    'data-selected' => $model->region_id,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->showDistrictField()): ?>
                        <div class="col-md-4">
                            <?= $form->field($model, 'district_id')->widget(Select2::class, [
                                'data' => CountryUnits::getListData('id', 'name', false, ['country_id' => $model->country_id, 'level' => CountryUnits::LEVEL_DISTRICT]),
                                'options' => [
                                    'class' => 'form-control parent-depdropdown',
                                    'placeholder' => '[select one]',
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'ward_id'),
                                    ],
                                    'data-url' => Url::to(['OrganizationRef-units/get-list', 'parent_id' => 'idV', 'placeholder' => true, 'level' => CountryUnits::LEVEL_DISTRICT]),
                                    'data-selected' => $model->district_id,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->showWardField()): ?>
                        <div class="col-md-4">
                            <?= $form->field($model, 'ward_id')->widget(Select2::class, [
                                'data' => CountryUnits::getListData('id', 'name', false, ['country_id' => $model->country_id, 'level' => CountryUnits::LEVEL_WARD]),
                                'options' => [
                                    'class' => 'form-control parent-depdropdown',
                                    'placeholder' => '[select one]',
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'village_id'),
                                    ],
                                    'data-url' => Url::to(['OrganizationRef-units/get-list', 'parent_id' => 'idV', 'placeholder' => true, 'level' => CountryUnits::LEVEL_WARD]),
                                    'data-selected' => $model->ward_id,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->showVillageField()): ?>
                        <div class="col-md-4">
                            <?= $form->field($model, 'village_id')->widget(Select2::class, [
                                'data' => CountryUnits::getListData('id', 'name', false, ['country_id' => $model->country_id, 'level' => CountryUnits::LEVEL_VILLAGE]),
                                'options' => [
                                    'class' => 'form-control parent-depdropdown',
                                    'placeholder' => '[select one]',
                                    'data-url' => Url::to(['OrganizationRef-units/get-list', 'parent_id' => 'idV', 'placeholder' => true, 'level' => CountryUnits::LEVEL_VILLAGE]),
                                    'data-selected' => $model->village_id,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]) ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-4">
                        <?= $form->field($model, 'name') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'farm_id')->widget(Select2::class, [
                            'data' => Farm::getListData(),
                            'options' => [
                                'class' => 'form-control parent-depdropdown',
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'org_id')->widget(Select2::class, [
                            'data' => Organization::getListData('id', 'name', true, ['country_id' => $model->country_id]),
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => [
                                'class' => 'form-control parent-depdropdown',
                                'placeholder' => '[select one]',
                                'data-url' => Url::to(['/core/organization/get-list', 'country_id' => 'idV', 'placeholder' => true]),
                                'data-selected' => $model->org_id,
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'client_id')->widget(Select2::class, [
                            'data' => Client::getListData('id', 'name', true, ['country_id' => $model->country_id]),
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => [
                                'class' => 'form-control parent-depdropdown',
                                'placeholder' => '[select one]',
                                'data-url' => Url::to(['/core/client/get-list', 'org_id' => 'idV', 'country_id' => $model->country_id, 'placeholder' => true]),
                                'data-selected' => $model->client_id,
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <?php foreach ($model->getAdditionalAttributes() as $attribute): ?>
                        <div class="col-md-4">
                            <?= $model->renderAdditionalAttribute($form, $attribute) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-md-8 offset-md-1">
                    <button type="submit"
                            class="btn btn-success"><?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?></button>
                    <a class="btn btn-secondary" href="<?= Url::getReturnUrl(Url::to(['index'])) ?>">
                        <?= Lang::t('Cancel') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>