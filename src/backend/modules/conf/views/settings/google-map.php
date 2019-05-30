<?php

use backend\modules\conf\settings\GoogleMapSettings;
use common\helpers\Lang;
use yii\bootstrap\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model GoogleMapSettings */
$this->title = Lang::t('Google Map Settings');
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
                        'label' => 'col-md-2 col-form-label',
                        'wrapper' => 'col-md-8',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
            ]);
            ?>
            <div class="kt-portlet__body">
                <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']); ?>
                <?= $form->field($model, GoogleMapSettings::KEY_API_KEY); ?>
                <?= $form->field($model, GoogleMapSettings::KEY_DEFAULT_MAP_CENTER); ?>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-3 col-xl-3">
                        </div>
                        <div class="col-lg-9 col-xl-9">
                            <button type="submit"
                                    class="btn btn-success"><?= Lang::t('Save Changes') ?></button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>