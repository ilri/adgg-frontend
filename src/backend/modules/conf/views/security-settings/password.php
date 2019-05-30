<?php

use backend\modules\conf\settings\PasswordSettings;
use common\helpers\Lang;
use common\widgets\select2\Select2;
use yii\bootstrap\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model backend\modules\conf\settings\PasswordSettings */

$this->title = 'Password Settings';
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
                        'id' => 'settings-form',
                        'layout' => 'horizontal',
                        'fieldClass' => \common\forms\ActiveField::class,
                        'fieldConfig' => [
                            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                            'horizontalCssClasses' => [
                                'label' => 'col-md-2 col-form-label',
                                'offset' => 'offset-md-2',
                                'wrapper' => 'col-md-6',
                                'error' => '',
                                'hint' => '',
                            ],
                        ],
                    ]);
                    ?>
                    <div class="kt-portlet__body">
                        <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']); ?>
                        <?= $form->field($model, PasswordSettings::KEY_USE_PRESET)->checkbox()->hint('Includes 5 presets (<strong>simple, normal, fair, medium, and strong</strong>).Instead of setting each parameter below, you can call a preset which will auto-set each of the parameters below.'); ?>
                        <?= $form->field($model, PasswordSettings::KEY_PRESET)->widget(Select2::class, [
                            'data' => $model::presetOptions(),
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $form->field($model, PasswordSettings::KEY_MIN_LENGTH)->textInput(['type' => 'number', 'step' => 1, 'min' => '4', 'max' => '20']); ?>
                        <?= $form->field($model, PasswordSettings::KEY_MAX_LENGTH)->textInput(['type' => 'number', 'step' => 1, 'min' => '4', 'max' => '20']); ?>
                        <?= $form->field($model, PasswordSettings::KEY_MIN_LOWER)->hint('')->textInput(['type' => 'number', 'min' => '0', 'max' => '20']); ?>
                        <?= $form->field($model, PasswordSettings::KEY_MIN_UPPER)->hint('')->textInput(['type' => 'number', 'min' => '0', 'max' => '20']); ?>
                        <?= $form->field($model, PasswordSettings::KEY_MIN_DIGIT)->hint('')->textInput(['type' => 'number', 'min' => '0', 'max' => '20']); ?>
                        <?= $form->field($model, PasswordSettings::KEY_MIN_SPECIAL)->hint('')->textInput(['type' => 'number', 'min' => '0', 'max' => '20']); ?>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-2">
                                </div>
                                <div class="col-10">
                                    <button type="submit"
                                            class="btn btn-success"><?= Lang::t('Save changes') ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php
$options = [];
$this->registerJs("MyApp.modules.conf.initPasswordSettings(" . Json::encode($options) . ");");
?>