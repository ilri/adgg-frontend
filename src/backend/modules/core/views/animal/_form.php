<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Choices;
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
                            'data' => Farm::getListData('id', 'name', false, []),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'code')->textInput(['disabled' => true])->hint('System generated') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'tag_id') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'name') ?>
                    </div>
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
                        <?= $form->field($model, 'estimate_age')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_KNOWN_DATE_OF_BIRTH),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'birthdate')->textInput(['class' => 'form-control show-datepicker', 'data-max-date' => DateUtils::getToday()]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'body_condition_score')->textInput(['type' => 'number', 'min' => 1, 'max' => 5]) ?>
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
                        <?= $form->field($model, 'udder_support')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_UDDER_SCORE),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'udder_attachment')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_UDDER_SCORE),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'udder_teat_placement')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_UDDER_SCORE),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'sire_registered')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_YESNO),
                            'options' => [
                                'placeholder' => '[select one]',
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
                        <?= $form->field($model, 'dam_registered')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_YESNO),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
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
                        <?= $form->field($model, 'is_genotyped')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_YESNO),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'genotype_id') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'result_genotype') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'first_calv_date')->textInput(['class' => 'form-control show-datepicker', 'data-max-date' => DateUtils::getToday()]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'first_calv_age')->textInput(['class' => 'form-control', 'type' => 'number']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'first_calv_date_estimate')->textInput(['class' => 'form-control show-datepicker', 'data-max-date' => DateUtils::getToday()]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'first_calv_method')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_CALVING_METHOD),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'first_calv_type')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_CALVING_TYPE),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'latest_calv_date')->textInput(['class' => 'form-control show-datepicker', 'data-max-date' => DateUtils::getToday()]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'latest_calv_date_estimate')->textInput(['class' => 'form-control show-datepicker', 'data-max-date' => DateUtils::getToday()]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'latest_calv_type')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_CALVING_TYPE),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'parity_number')->textInput(['type' => 'number']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'average_daily_milk')->textInput(['type' => 'number']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'peak_milk')->textInput(['type' => 'number']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'is_still_lactating')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_YESNO),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'dry_date')->textInput(['class' => 'form-control show-datepicker', 'data-max-date' => DateUtils::getToday()]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'is_pregnant')->widget(Select2::class, [
                            'data' => Choices::getList(ChoiceTypes::CHOICE_TYPE_YESNO),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'entry_date')->textInput(['class' => 'form-control show-datepicker', 'data-max-date' => DateUtils::getToday()]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'entry_type')->widget(Select2::class, [
                            'data' => Choices::getList(99),
                            'options' => [
                                'placeholder' => '[select one]',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'purchase_cost')->textInput() ?>
                    </div>

                    <?php foreach ($model->getAdditionalAttributes() as $attribute): ?>
                        <div class="col-md-4">
                            <?= $model->renderAdditionalAttribute($form, $attribute) ?>
                        </div>
                    <?php endforeach; ?>
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