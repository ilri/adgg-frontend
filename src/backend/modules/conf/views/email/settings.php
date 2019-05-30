<?php

use backend\modules\conf\settings\EmailSettings;
use common\helpers\Lang;
use common\widgets\select2\Select2;
use vova07\imperavi\Widget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model EmailSettings */

$this->title = 'Email Settings';
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
                    <?= $form->field($model, EmailSettings::KEY_HOST); ?>
                    <?= $form->field($model, EmailSettings::KEY_PORT); ?>
                    <?= $form->field($model, EmailSettings::KEY_USERNAME)->hint('e.g noreply@domain.com'); ?>
                    <?= $form->field($model, EmailSettings::KEY_PASSWORD)->passwordInput([])->hint(Lang::t('Password for the username.')); ?>
                    <?= $form->field($model, EmailSettings::KEY_SECURITY)->widget(Select2::class, [
                        'data' => ['' => 'NULL', 'ssl' => 'SSL', 'tls' => 'TLS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]) ?>
                    <?= $form->field($model, EmailSettings::KEY_THEME)->widget(Widget::class, [
                        'settings' => [
                            'minHeight' => 150,
                            'replaceDivs' => false,
                            'paragraphize' => true,
                            'cleanOnPaste' => true,
                            'removeWithoutAttr' => [],
                            'imageManagerJson' => Url::to(['/redactor/fetch-images']),
                            'imageUpload' => Url::to(['/redactor/image-upload']),
                            'plugins' => [
                                'fullscreen',
                                'imagemanager',
                            ],
                        ],
                    ])->hint('Make sure that "{{content}}" placeholder is not removed.');
                    ?>
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