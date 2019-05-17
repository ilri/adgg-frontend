<?php

use backend\widgets\Alert;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model \backend\modules\auth\forms\PasswordResetRequestForm */

$this->title = Lang::t('Request Password Reset');
?>

<div class="kt-login-v2__container" style="padding: 1.2rem;">
    <div class="kt-login-v2__title">
        <h3 style="color: #004730;font-size: 24px;font-weight: 600;">Request Password Reset</h3>
    </div>

    <!--begin::Form-->
    <?php
    $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => [
            'class' => 'kt-login-v2__form kt-form',
            'autocomplete' => 'off',
        ],
        // 'enableClientValidation' => false,
        'enableAjaxValidation' => false,
    ]);
    ?>
    <?= Html::errorSummary($model, ['class' => 'alert alert-warning','header'=>'']) ?>
    <?= Alert::widget(); ?>
    <div class="form-group">
        <?= Html::activeTextInput($model, 'email', ['class' => 'form-control', 'placeholder' => 'Enter your Email', 'autocomplete' => 'off', 'style' => 'font-size: 1.2rem;']); ?>
    </div>

    <!--begin::Action-->
    <div class="kt-login-v2__actions">
        <a href="<?= Url::to(['login']) ?>" class="kt-link kt-link--brand">
            <i class="fa fa-chevron-circle-left"></i> Back to Login Page
        </a>
        <button type="submit" class="btn btn-brand btn-elevate btn-pill">Submit</button>
    </div>
    <!--end::Action-->
    <?php ActiveForm::end(); ?>
    <!--end::Form-->
</div>