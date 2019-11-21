<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalHerd;
use backend\modules\core\models\Farm;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Choices;
use backend\modules\core\models\Organization;
use backend\modules\core\models\OrganizationUnits;
use common\forms\ActiveField;
use common\helpers\DateUtils;
use common\widgets\select2\Select2;
use yii\bootstrap\Html;
use common\helpers\Url;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model Animal */
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
        'id' => 'animal-form',
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
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'farm_id')->widget(Select2::class, [
                            'data' => Farm::getListData(),
                            'options' => [
                                'class' => 'form-control parent-depdropdown',
                                'placeholder' => '[select one]',
                                'data-child-selectors' => [
                                    '#' . Html::getInputId($model, 'herd_id'),
                                ],
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'herd_id')->widget(Select2::class, [
                            'data' => AnimalHerd::getListData('id', 'name', false, []),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'tag_id') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'name') ?>
                    </div>
                    <?php if ($model->showCountryField()): ?>
                        <div class="col-md-4">
                            <?= $form->field($model, 'org_id')->widget(Select2::class, [
                                'data' => Organization::getListData(),
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
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['org_id' => $model->org_id, 'level' => OrganizationUnits::LEVEL_REGION]),
                                'options' => [
                                    'class' => 'form-control parent-depdropdown',
                                    'placeholder' => '[select one]',
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'district_id'),
                                    ],
                                    'data-url' => Url::to(['organization-units/get-list', 'org_id' => 'idV', 'level' => OrganizationUnits::LEVEL_REGION]),
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
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['org_id' => $model->org_id, 'level' => OrganizationUnits::LEVEL_DISTRICT]),
                                'options' => [
                                    'class' => 'form-control parent-depdropdown',
                                    'placeholder' => '[select one]',
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'ward_id'),
                                    ],
                                    'data-url' => Url::to(['organization-units/get-list', 'parent_id' => 'idV', 'level' => OrganizationUnits::LEVEL_DISTRICT]),
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
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['org_id' => $model->org_id, 'level' => OrganizationUnits::LEVEL_WARD]),
                                'options' => [
                                    'class' => 'form-control parent-depdropdown',
                                    'placeholder' => '[select one]',
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'village_id'),
                                    ],
                                    'data-url' => Url::to(['organization-units/get-list', 'parent_id' => 'idV', 'level' => OrganizationUnits::LEVEL_WARD]),
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
                                'data' => OrganizationUnits::getListData('id', 'name', false, ['org_id' => $model->org_id, 'level' => OrganizationUnits::LEVEL_VILLAGE]),
                                'options' => [
                                    'class' => 'form-control parent-depdropdown',
                                    'placeholder' => '[select one]',
                                    'data-url' => Url::to(['organization-units/get-list', 'parent_id' => 'idV', 'level' => OrganizationUnits::LEVEL_VILLAGE]),
                                    'data-selected' => $model->village_id,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]) ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-4">
                        <?= $form->field($model, 'animal_type')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'color') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'birthdate')->textInput(['class' => 'form-control show-datepicker', 'data-max-date' => DateUtils::getToday()]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'is_derived_birthdate')->checkbox() ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'deformities')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_CALVE_DEFORMITY),
                            'options' => [
                                'placeholder' => '[select one]',
                                'multiple' => true,
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'sire_type')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_SIRE_TYPE),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'sire_id')->widget(Select2::class, [
                            'data' => Animal::getListData('id', 'name', false, ['animal_type' => [Animal::ANIMAL_TYPE_BULL, Animal::ANIMAL_TYPE_AI_STRAW]]),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'sire_tag_id') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'sire_name') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'bull_straw_id') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'dam_id')->widget(Select2::class, [
                            'data' => Animal::getListData('id', 'name', false, ['animal_type' => [Animal::ANIMAL_TYPE_COW, Animal::ANIMAL_TYPE_HEIFER]]),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'dam_tag_id') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'dam_name') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'main_breed')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'breed_composition')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_BREED_COMPOSITION),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'secondary_breed')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'entry_type')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_ENTRY_TYPE),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'entry_date')->textInput() ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'purchase_cost')->textInput() ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'latitude')->textInput() ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'longitude')->textInput() ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'map_address')->textInput() ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'latlng')->textInput() ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'purchase_cost')->textInput() ?>
                    </div>
                    <?php foreach ($model->getAdditionalAttributes() as $attribute): ?>
                        <div class="col-md-4">
                            <?= $model->renderAdditionalAttribute($form, $attribute) ?>
                        </div>
                    <?php endforeach; ?>
                    <div class="col-md-4">
                        <?= $this->render('_photoField', ['model' => $model]) ?>
                    </div>

                </div>
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