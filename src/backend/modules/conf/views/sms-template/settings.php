<?php

use backend\modules\conf\settings\SmsSettings;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model SmsSettings */

$this->title = 'SMS Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-2">
        <?= $this->render('@app/modules/conf/views/layouts/_submenu'); ?>
    </div>
    <div class="col-lg-10">
        <?= $this->render('_tab'); ?>
        <div class="tab-content">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= Html::encode($this->title) ?></h3>
                    </div>
                </div>
                <?php
                $form = ActiveForm::begin([
                    'id' => 'email-template-form',
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                        'horizontalCssClasses' => [
                            'label' => 'col-md-2 col-form-label',
                            'offset' => 'offset-md-2',
                            'wrapper' => 'col-md-8',
                            'error' => '',
                            'hint' => '',
                        ],
                    ],
                ]);
                ?>
                <div class="kt-portlet__body">
                    <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']); ?>
                    <?= $form->field($model, SmsSettings::KEY_BASE_URL) ?>
                    <?= $form->field($model, SmsSettings::KEY_DEFAULT_SENDER_ID) ?>
                    <?= $form->field($model, SmsSettings::KEY_USERNAME) ?>
                    <?= $form->field($model, SmsSettings::KEY_PASSWORD)->passwordInput() ?>
                    <?= $form->field($model, SmsSettings::KEY_API_KEY) ?>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-2">
                            </div>
                            <div class="col-10">
                                <button type="submit" class="btn btn-success"><?= Lang::t('Save changes') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>