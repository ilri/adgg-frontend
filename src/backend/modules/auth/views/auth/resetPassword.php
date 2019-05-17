<?php

use backend\widgets\Alert;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model \backend\modules\auth\forms\ResetPasswordForm */

$this->title = Lang::t('Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kt-login-v2__container" style="padding: 1.2rem;">
    <div class="kt-login-v2__title">
        <h3 style="color: #004730;font-size: 24px;font-weight: 600;">Reset Password</h3>
    </div>

    <!--begin::Form-->
    <?php
    $form = ActiveForm::begin([
        'id' => 'reset-password-form',
        'options' => [
            'class' => 'kt-login-v2__form kt-form',
            'autocomplete' => 'off',
        ],
        // 'enableClientValidation' => false,
        'enableAjaxValidation' => false,
    ]);
    ?>
    <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']) ?>
    <?= Alert::widget(); ?>
    <div class="form-group">
        <?= Html::activeHiddenInput($model, 'username'); ?>
        <?= Html::activePasswordInput($model, 'password', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('password'), 'autocomplete' => 'off', 'style' => 'font-size: 1.2rem;']); ?>
        <p class="help-block text-muted">
            At least 8 characters with at least 1 number,1 lower case and 1 upper case letter.
        </p>
    </div>
    <div class="form-group">
        <?= Html::activePasswordInput($model, 'confirm', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('confirm'), 'autocomplete' => 'off', 'style' => 'font-size: 1.2rem;']); ?>
    </div>

    <!--begin::Action-->
    <div class="kt-login-v2__actions">
        <a href="<?= Url::to(['login']) ?>" class="kt-link kt-link--brand">
            <i class="fa fa-chevron-circle-left"></i> Back to Login Page
        </a>
        <button type="submit" class="btn btn-brand btn-elevate btn-pill">Reset Password</button>
    </div>
    <!--end::Action-->
    <?php ActiveForm::end(); ?>
    <!--end::Form-->
</div>