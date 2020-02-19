<?php

use backend\modules\core\models\Country;
use backend\modules\core\models\OrganizationRef;
use common\forms\ActiveField;
use common\widgets\select2\Select2;
use yii\bootstrap\Html;
use common\helpers\Url;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model OrganizationRef */
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
        'id' => 'OrganizationRef-form',
        'layout' => 'horizontal',
        'options' => ['class' => 'kt-form kt-form--label-right'],
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
                        <?= $form->field($model, 'country')->widget(Select2::class, [
                            'data' => Country::getListData('iso2', 'name', false),
                            'options' => ['placeholder' => '[select one]'],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, 'dialing_code') ?>
                        <?= $form->field($model, 'code') ?>
                        <?= $form->field($model, 'contact_person') ?>
                        <?= $form->field($model, 'contact_phone') ?>
                        <?= $form->field($model, 'contact_email') ?>
                        <?= $form->field($model, 'unit1_name') ?>
                        <?= $form->field($model, 'unit2_name') ?>
                        <?= $form->field($model, 'unit3_name') ?>
                        <?= $form->field($model, 'unit4_name') ?>
                        <?php if (!$model->isNewRecord): ?>
                            <?= $form->field($model, 'is_active')->checkbox() ?>
                        <?php endif; ?>
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