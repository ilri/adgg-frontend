<?php

use backend\widgets\Alert;
use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \backend\modules\auth\forms\LoginForm */
?>
<div class="kt-login-v2__container">
    <div class="kt-login-v2__title">
        <h3 class="mt-2" id="site-title">ADGG Data Platform</h3>
        <h3 class="text-left" id="login-cta">Login to your account</h3>
    </div>

    <!--begin::Form-->
    <?php
    $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => [
            'class' => 'kt-login-v2__form kt-form',
            'autocomplete' => 'off',
        ],
        'enableClientValidation' => false,
        'enableAjaxValidation' => false,
    ]);
    ?>
    <?= Alert::widget(); ?>
    <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']) ?>
    <div class="form-group">
        <?php if ($model->scenario === 'lwe'): ?>
            <?= Html::activeTextInput($model, 'email', ['class' => 'form-control', 'placeholder' => 'Email', 'autocomplete' => 'off', 'style' => 'font-size: 1.2rem;']); ?>
        <?php else: ?>
            <?= Html::activeTextInput($model, 'username', ['class' => 'form-control', 'placeholder' => 'Username', 'autocomplete' => 'off', 'style' => 'font-size: 1.2rem;']); ?>
        <?php endif ?>
    </div>
    <div class="form-group">
        <?= Html::activePasswordInput($model, 'password', ['class' => 'form-control', 'placeholder' => 'Password', 'autocomplete' => 'off', 'style' => 'font-size: 1.2rem;']); ?>
    </div>
    <div class="form-group">
        <?php if ($model->getIsVerifyRobotRequired()) : ?>
            <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                'captchaAction' => ['captcha'],
                'template' => '{image}{input}',
            ]) ?>
        <?php endif; ?>
    </div>

    <!--begin::Action-->
    <div class="kt-login-v2__actions">
        <a href="<?= Url::to(['request-password-reset']) ?>" class="kt-link kt-link--brand">
            Forgot Password ?
        </a>
        <button type="submit" class="btn btn-brand btn-elevate btn-pill">Sign In</button>
    </div>

    <!--end::Action-->
    <?php ActiveForm::end(); ?>
    <!--end::Form-->
</div>
