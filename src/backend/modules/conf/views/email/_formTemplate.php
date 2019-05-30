<?php

use common\helpers\Lang;
use common\helpers\Url;
use yii\bootstrap\Html;
use yii\bootstrap4\ActiveForm;

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
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-xs-2 col-sm-1 col-form-label',
                'offset' => 'col-xs-offset-2 col-sm-offset-1',
                'wrapper' => 'col-sm-6 col-xs-10',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]);
    ?>
    <div class="kt-portlet__body">
        <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']); ?>
         {fields}
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-xs-2 col-sm-1">
                </div>
                <div class="col-xs-10">
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