<?php

use backend\modules\auth\models\Roles;
use backend\modules\auth\models\Users;
use backend\modules\auth\Session;
use backend\modules\conf\models\NotifTypes;
use common\forms\ActiveField;
use common\widgets\select2\Select2;
use yii\bootstrap\Html;
use common\helpers\Url;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model backend\modules\conf\models\NotifTypes */
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
            'id' => 'notif-type-form',
            'layout' => 'horizontal',
            'options' => ['class' => 'kt-form'],
            'fieldClass' => ActiveField::class,
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-2 col-form-label',
                    'offset' => 'offset-2',
                    'wrapper' => 'col-6',
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
                    <h3 class="kt-section__title kt-section__title-lg"><?= Lang::t('Notification details') ?></h3>
                    <?php if (Session::isDev()): ?>
                        <?= $form->field($model, 'id', []); ?>
                        <?= $form->field($model, 'name'); ?>
                        <?= $form->field($model, 'description')->textarea(['rows' => 3]); ?>
                        <?= $form->field($model, 'model_class_name'); ?>
                        <?= $form->field($model, 'notification_trigger')->widget(Select2::class, [
                            'data' => NotifTypes::notificationTriggerOptions(),
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, 'max_notifications'); ?>
                        <?= $form->field($model, 'notification_time')->hint('Time in this format: HH:MM e.g 08:00'); ?>
                        <?= $form->field($model, 'fa_icon_class'); ?>
                    <?php endif ?>
                    <?= $form->field($model, 'enable_internal_notification')->checkbox(['class' => 'checkbox']); ?>
                    <?= $form->field($model, 'template')->textarea(['rows' => 3])->hint(
                        'Template for displaying notification within this system<br>Please do not remove placeholders (terms enclosed in {})'
                    ); ?>
                    <?= $form->field($model, 'enable_email_notification')->checkbox(); ?>
                    <?= $form->field($model, 'email_template_id')->widget(Select2::class, [
                        'data' => \backend\modules\conf\models\EmailTemplate::getListData('id', 'name'),
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]) ?>
                    <?= $form->field($model, 'enable_sms_notification')->checkbox(); ?>
                    <?= $form->field($model, 'sms_template_id')->widget(Select2::class, [
                        'data' => \backend\modules\conf\models\SmsTemplate::getListData('id', 'name'),
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]) ?>
                    <?= $form->field($model, 'is_active')->checkbox(); ?>
                </div>
            </div>
            <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
            <div class="kt-section kt-section--last">
                <div class="kt-section__body">
                    <h3 class="kt-section__title kt-section__title-lg"><?= Lang::t('People to notify') ?></h3>
                    <?= $form->field($model, 'notify_all_users')->checkbox(); ?>
                    <?= $form->field($model, 'users')->widget(Select2::class, [
                        'data' => Users::getListData('id', 'name'),
                        'options' => ['multiple' => true],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]) ?>
                    <?= $form->field($model, 'roles')->widget(Select2::class, [
                        'data' => Roles::getListData('id', 'name'),
                        'options' => ['multiple' => true],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]) ?>
                    <?= $form->field($model, 'email')->textarea(['class' => 'form-control', 'rows' => 3])->hint('Comma separated email addresses to receive email notification.'); ?>
                    <?php //echo $form->field($model, 'phone')->hint('Comma separated phone numbers to receive SMS notification.'); ?>
                </div>
            </div>
        </div>

        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <div class="row">
                    <div class="col-10 offset-2">
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
<?php
$options = [
    'modelClass' => strtolower($model->shortClassName()),
];
$this->registerJs("MyApp.modules.conf.notificationSettings(" . Json::encode($options) . ");");
?>