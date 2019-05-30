<?php

use backend\modules\auth\models\UserLevels;
use backend\modules\conf\models\NotifTypes;
use backend\modules\core\models\Country;
use backend\modules\core\models\County;
use backend\modules\core\models\Organization;
use common\forms\ActiveField;
use common\helpers\DateUtils;
use common\widgets\select2\Select2;
use yii\bootstrap\Html;
use common\helpers\Url;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Organization */
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
        'id' => 'organization-form',
        'layout' => 'horizontal',
        'options' => ['class' => 'kt-form'],
        'fieldClass' => ActiveField::class,
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-md-4 col-form-label',
                'offset' => 'offset-md-4',
                'wrapper' => 'col-md-7',
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
                    <div class="col-md-6">
                        <h3 class="kt-section__title kt-section__title-lg">
                            <?= Lang::t('Business Information') ?>
                        </h3>
                        <?= $form->field($model, 'name'); ?>
                        <?= $form->field($model, 'business_type')->widget(Select2::class, [
                            'data' => Organization::businessTypeOptions(),
                            'options' => ['placeholder' => '[select one]'],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, 'business_entity_type')->widget(Select2::class, [
                            'data' => Organization::businessEntityTypeOptions(),
                            'options' => ['placeholder' => '[select one]'],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, 'account_manager_id')->widget(Select2::class, [
                            'data' => \backend\modules\auth\models\Users::getListData('id', 'name', false, '[[level_id]]<>:level_id', [':level_id' => UserLevels::LEVEL_ORGANIZATION]),
                            'options' => ['placeholder' => '[select one]'],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, 'daily_customers')->widget(Select2::class, [
                            'data' => Organization::dailyCustomersOptions(),
                            'options' => ['placeholder' => '[select one]'],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, 'application_date')->textInput(['class' => 'form-control show-datepicker', 'data-max-date' => DateUtils::getToday()]) ?>
                        <?= $form->field($model, 'applicant_name') ?>
                        <?= $form->field($model, 'applicant_phone') ?>
                        <?= $form->field($model, 'applicant_business_ownership_type')->widget(Select2::class, [
                            'data' => Organization::businessOwnershipTypeOptions(),
                            'options' => ['placeholder' => '[select one]'],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, 'contact_first_name') ?>
                        <?= $form->field($model, 'contact_last_name') ?>
                        <?= $form->field($model, 'contact_phone') ?>
                        <?= $form->field($model, 'contact_alt_phone') ?>
                        <?= $form->field($model, 'contact_email') ?>
                        <?= $form->field($model, 'is_credit_requested')->checkbox() ?>
                    </div>
                    <div class="col-md-6">
                        <h3 class="kt-section__title kt-section__title-lg">
                            <?= Lang::t('Location Information') ?>
                        </h3>
                        <?= $form->field($model, 'country')->widget(Select2::class, [
                            'data' => Country::getListData('iso2', 'name', false),
                            'options' => ['placeholder' => '[select one]'],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, 'county')->widget(Select2::class, [
                            'data' => County::getListData('name', 'name', false),
                            'options' => ['placeholder' => '[select one]'],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>

                        <?= $form->field($model, 'sub_county') ?>
                        <?= $form->field($model, 'street') ?>
                        <?= $form->field($model, 'postal_address') ?>
                        <div class="row">
                            <div class="col-md-11">
                                <?= \common\widgets\gmap\GmapGeocode::widget([
                                    'model' => $model,
                                    'geocodeUrl' => Url::to(['/helper/gmap-geocode']),
                                    'latitudeAttribute' => 'map_latitude',
                                    'longitudeAttribute' => 'map_longitude',
                                    'addressAttribute' => 'map_address',
                                    'showLatitudeLabel' => true,
                                    'showLongitudeLabel' => true,
                                    'mapWrapperHtmlOptions' => ['style' => 'height:300px;'],
                                    'showAddressLabel' => false,
                                    'latitude' => $model->map_latitude,
                                    'longitude' => $model->map_longitude,
                                ])
                                ?>
                            </div>
                        </div>
                        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                        <?= $this->render('_logoField', ['model' => $model]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-md-8 offset-md-2">
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