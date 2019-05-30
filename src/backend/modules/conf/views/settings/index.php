<?php

use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\Currency;
use common\helpers\Lang;
use backend\modules\conf\models\Timezone;
use backend\modules\core\models\Country;
use common\helpers\Utils;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model SystemSettings */
$this->title = Lang::t('General Settings');
$this->params['breadcrumbs'] = [
    $this->title
];
?>
<div class="row">
    <div class="col-md-2">
        <!--begin::Portlet-->
        <?= $this->render('@app/modules/conf/views/layouts/_submenu'); ?>
        <!--end::Portlet-->
    </div>
    <div class="col-md-10">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title"><?= $this->title ?></h3>
                </div>
            </div>
            <?php
            $form = ActiveForm::begin([
                'id' => 'settings-form',
                'layout' => 'horizontal',
                'options' => ['class' => 'kt-form kt-form--label-right'],
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-md-2 col-form-label',
                        'wrapper' => 'col-md-8',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
            ]);
            ?>

            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                        <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']); ?>
                        <?php if (!\backend\modules\auth\Session::isOrganization()): ?>
                            <?= $form->field($model, SystemSettings::KEY_COMPANY_NAME); ?>
                            <?= $form->field($model, SystemSettings::KEY_APP_NAME); ?>
                            <?= $form->field($model, SystemSettings::KEY_COMPANY_EMAIL); ?>
                        <?php endif ?>
                        <?= $form->field($model, SystemSettings::KEY_DEFAULT_TIMEZONE)->widget(Select2::class, [
                            'data' => Timezone::getListData(),
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, SystemSettings::KEY_DEFAULT_COUNTRY)->widget(Select2::class, [
                            'data' => Country::getListData('iso2', 'name'),
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, SystemSettings::KEY_DEFAULT_CURRENCY)->widget(Select2::class, [
                            'data' => Currency::getListData('iso3', 'iso3', false),
                            'pluginOptions' => [
                                'allowClear' => false,
                            ],
                        ]) ?>
                        <?= $form->field($model, SystemSettings::KEY_PAGINATION_SIZE)->widget(Select2::class, [
                            'data' => Utils::generateIntegersList(50, 1000, 50),
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, SystemSettings::KEY_DEFAULT_THEME)->widget(Select2::class, [
                            'data' => $model::themeOptions(),
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-3 col-xl-3">
                        </div>
                        <div class="col-lg-9 col-xl-9">
                            <button type="submit"
                                    class="btn btn-success"><?= Lang::t('Save Changes') ?></button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>