<?php

use common\helpers\Lang;
use common\helpers\Url;
use yii\bootstrap\Html;
use vova07\imperavi\Widget;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\conf\models\EmailTemplate */
/* @var $form  ActiveForm */
?>
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title"><?= Html::encode($this->title) ?></h3>
        </div>
    </div>

    <!--begin::Form-->
    <?php
    $form = ActiveForm::begin([
        'id' => 'email-template-form',
        'layout' => 'horizontal',
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'options' => ['class'=>'kt-form kt-form--label-right'],
        'fieldClass' => \common\forms\ActiveField::class,
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-md-2 col-form-label',
                'offset' => 'offset-md-2',
                'wrapper' => 'col-md-10',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]);
    ?>
    <div class="kt-portlet__body">
        <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']); ?>
        <?php if (\backend\modules\auth\Session::isDev()): ?>
            <?= $form->field($model, 'id', []); ?>
        <?php endif ?>

        <?= $form->field($model, 'name'); ?>

        <?= $form->field($model, 'subject', [])->textInput([]); ?>

        <?= $form->field($model, 'sender', [])->textInput([]); ?>

        <?= $form->field($model, 'body')->widget(Widget::class, [
            'settings' => [
                'minHeight' => 150,
                'imageManagerJson' => Url::to(['/redactor/fetch-images']),
                'imageUpload' => Url::to(['/redactor/image-upload']),
                'replaceDivs' => false,
                'paragraphize' => true,
                'cleanOnPaste' => true,
                'removeWithoutAttr' => [],
                'plugins' => [
                    'fullscreen',
                    'imagemanager',
                ],
            ],
        ])->hint(Lang::t('NOTE: Please DO NOT remove placeholders (words enclosed with {{}}). You are
                free to reorganize the body template and add other words or html tags but do not remove the
                placeholders'));
        ?>
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-md-2">
                </div>
                <div class="col-md-10">
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