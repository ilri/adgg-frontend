<?php

use backend\modules\auth\models\UserLevels;
use backend\modules\auth\models\Users;
use backend\modules\auth\Session;
use common\forms\ActiveField;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use kartik\password\PasswordInput;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Json;

/* @var $this \yii\web\View */
/* @var $model backend\modules\auth\models\Users */
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
            'id' => 'user-form',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'kt-form kt-form--label-right'],
            'fieldClass' => ActiveField::class,
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-lg-3 col-form-label',
                    'offset' => 'offset-lg-3',
                    'wrapper' => 'col-lg-9',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]);
        ?>
        <div class="kt-portlet__body">
            <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']); ?>
            <?php if ($model->checkPermission(false, true)) : ?>
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'level_id')->widget(Select2::class, [
                            'data' => Users::levelIdListData(),
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => [
                                'class' => 'form-control parent-depdropdown',
                                'data-child-selectors' => [
                                    '#' . Html::getInputId($model, 'role_id'),
                                ],
                                'data-show-organization' => [UserLevels::LEVEL_ORGANIZATION]
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                    <?php if (!Session::isOrganization()): ?>
                        <div class="col-md-4" id="organization-id-wrapper">
                            <?= $form->field($model, 'org_id')->widget(Select2::class, [
                                'data' => \backend\modules\core\models\Organization::getListData(),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]) ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-4">
                        <?= $form->field($model, 'role_id')->widget(Select2::class, [
                            'data' => \backend\modules\auth\models\Roles::getListData('id', 'name', false, Session::isOrganization() ? ['level_id' => UserLevels::LEVEL_ORGANIZATION] : []),
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => [
                                'data-url' => Url::to(['role/get-list', 'level_id' => 'idV']),
                                'data-selected' => $model->role_id,
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                    </div>
                </div>
                <hr/>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'email') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'phone') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'timezone')->widget(Select2::class, [
                                'data' => \backend\modules\conf\models\Timezone::getListData(),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $this->render('_imageField', ['model' => $model]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'require_password_change')->checkbox() ?>
                            <?php if ($model->isNewRecord): ?>
                                <?= $form->field($model, 'send_email')->checkbox() ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'username') ?>
                            <?php if ($model->isNewRecord): ?>
                                <?= $form->field($model, 'auto_generate_password')->checkbox() ?>
                                <div id="password-fields-wrapper">
                                    <?= $form->field($model, 'password', [
                                        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                                    ])->widget(PasswordInput::class, [
                                        'pluginOptions' => [
                                            'showMeter' => true,
                                            'toggleMask' => true,
                                        ],
                                        'options' => ['class' => 'form-control']
                                    ]) ?>
                                    <?= $form->field($model, 'confirm')->widget(PasswordInput::class, [
                                        'pluginOptions' => [
                                            'showMeter' => false,
                                            'toggleMask' => true,
                                        ],
                                        'options' => ['class' => 'form-control']
                                    ]) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <div class="row">
                    <div class="offset-lg-1 col-lg-10">
                        <button type="submit" class="btn btn-success">
                            <?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?>
                        </button>
                        <a class="btn btn-secondary"
                           href="<?= Url::getReturnUrl($model->isNewRecord ? Url::to(['index', 'level_id' => $model->level_id]) : Url::to(['view', 'id' => $model->id])) ?>">
                            <?= Lang::t('Cancel') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$options = [
    'levelIdFieldSelector' => '#' . Html::getInputId($model, 'level_id'),
    'orgIdFieldSelector' => '#' . Html::getInputId($model, 'org_id'),
];
$this->registerJs("MyApp.modules.auth.initUserForm(" . Json::encode($options) . ");");
?>