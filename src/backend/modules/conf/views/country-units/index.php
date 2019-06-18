<?php

use common\helpers\Lang;
use yii\bootstrap\Html;
use yii\bootstrap4\ActiveForm;
use backend\modules\conf\settings\CountryAdministrativeUnits;

/* @var $this yii\web\View */
/* @var $model CountryAdministrativeUnits */
$this->title = Lang::t('Country Administrative Units Settings');
$this->params['breadcrumbs'] = [
    $this->title
];
?>
<div class="row">
    <div class="col-lg-2">
        <?= $this->render('@app/modules/conf/views/layouts/_submenu'); ?>
    </div>
    <div class="col-lg-10">
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
                        'label' => 'col-md-4 col-form-label',
                        'wrapper' => 'col-md-6',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
            ]);
            ?>
            <div class="kt-portlet__body">
                <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']); ?>
                <?= $form->field($model, CountryAdministrativeUnits::KEY_COUNTRY_UNIT_1); ?>
                <?= $form->field($model, CountryAdministrativeUnits::KEY_COUNTRY_UNIT_2); ?>
                <?= $form->field($model, CountryAdministrativeUnits::KEY_COUNTRY_UNIT_3); ?>
                <?= $form->field($model, CountryAdministrativeUnits::KEY_COUNTRY_UNIT_4); ?>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="offset-md-4 col-md-6">
                            <button type="submit" class="btn btn-success">
                                <?= Lang::t('Save Changes') ?>
                            </button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>