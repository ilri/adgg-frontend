<?php

use common\forms\ActiveField;
use common\helpers\Lang;
use common\helpers\Url;
use kartik\password\PasswordInput;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model \backend\modules\auth\models\Users */
?>
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title"><?= Html::encode($this->title) ?></h3>
            </div>
        </div>
        <?php
        $form = ActiveForm::begin([
            'id' => 'reset-password-form',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'kt-form kt-form--label-right'],
            'fieldClass' => ActiveField::class,
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-lg-2 col-form-label',
                    'offset' => 'offset-lg-2',
                    'wrapper' => 'col-lg-4',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]);
        ?>
        <div class="kt-portlet__body">
            <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']); ?>
            <?= $form->field($model, 'auto_generate_password')->checkbox() ?>
            <div id="password-fields-wrapper">
                <?= $form->field($model, 'password')->widget(PasswordInput::class, [
                    'pluginOptions' => [
                        'showMeter' => true,
                        'toggleMask' => true,
                    ],
                    'options' => ['class' => 'form-control disable-copy-paste']
                ]) ?>
                <?= $form->field($model, 'confirm')->widget(PasswordInput::class, [
                    'pluginOptions' => [
                        'showMeter' => false,
                        'toggleMask' => true,
                    ],
                    'options' => ['class' => 'form-control disable-copy-paste']
                ]) ?>
            </div>
            <?= $form->field($model, 'require_password_change')->checkbox() ?>
            <?= $form->field($model, 'send_email')->checkbox() ?>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <div class="row">
                    <div class="offset-lg-2 col-lg-10">
                        <button type="submit" class="btn btn-success">
                            <?= Lang::t('Reset Password') ?>
                        </button>
                        <a class="btn btn-secondary"
                           href="<?= Url::getReturnUrl(Url::to(['view', 'id' => $model->id])) ?>">
                            <?= Lang::t('Cancel') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$options = [];
$this->registerJs(" MyApp.modules.auth.autoGeneratePassword(" . Json::encode($options) . ");");
?>