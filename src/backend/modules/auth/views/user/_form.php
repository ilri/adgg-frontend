<?php

use backend\modules\auth\models\UserLevels;
use backend\modules\auth\models\Users;
use backend\modules\auth\Session;
use backend\modules\core\models\Client;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Organization;
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
            <div class="kt-section kt-section--first">
                <div class="kt-section__body">
                    <h3 class="kt-section__title kt-section__title-lg"><?= Lang::t('User details') ?></h3>
                    <div class="row">
                        <?php if ($model->checkPermission(false, true)) : ?>
                            <div class="col-md-4">
                                <?= $form->field($model, 'level_id')->widget(Select2::class, [
                                    'data' => Users::levelIdListData(),
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'class' => 'form-control parent-depdropdown',
                                        'data-child-selectors' => [
                                            '#' . Html::getInputId($model, 'role_id'),
                                        ],
                                        'data-show-country' => [UserLevels::LEVEL_COUNTRY, UserLevels::LEVEL_ORGANIZATION, UserLevels::LEVEL_ORGANIZATION_CLIENT, UserLevels::LEVEL_REGION, UserLevels::LEVEL_DISTRICT, UserLevels::LEVEL_WARD, UserLevels::LEVEL_VILLAGE],
                                        'data-show-organization' => [UserLevels::LEVEL_ORGANIZATION, UserLevels::LEVEL_ORGANIZATION_CLIENT],
                                        'data-show-client' => [UserLevels::LEVEL_ORGANIZATION_CLIENT],
                                        'data-show-region' => [UserLevels::LEVEL_REGION, UserLevels::LEVEL_DISTRICT, UserLevels::LEVEL_WARD, UserLevels::LEVEL_VILLAGE],
                                        'data-show-district' => [UserLevels::LEVEL_DISTRICT, UserLevels::LEVEL_WARD, UserLevels::LEVEL_VILLAGE],
                                        'data-show-ward' => [UserLevels::LEVEL_WARD, UserLevels::LEVEL_VILLAGE],
                                        'data-show-village' => [UserLevels::LEVEL_VILLAGE],
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => false
                                    ],
                                ]) ?>
                            </div>
                            <?php if (!Session::isCountry()): ?>
                                <div class="col-md-4" id="country-id-wrapper">
                                    <?= $form->field($model, 'country_id')->widget(Select2::class, [
                                        'data' => \backend\modules\core\models\Country::getListData(),
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'class' => 'form-control parent-depdropdown',
                                            'data-child-selectors' => [
                                                '#' . Html::getInputId($model, 'region_id'),
                                                '#' . Html::getInputId($model, 'org_id'),

                                            ],
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-md-4" id="org-id-wrapper">
                                    <?= $form->field($model, 'org_id')->widget(Select2::class, [
                                        'data' => Organization::getListData('id', 'name', true, ['country_id' => $model->country_id]),
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'class' => 'form-control parent-depdropdown',
                                            'placeholder' => '[select one]',
                                            'data-url' => Url::to(['/core/organization/get-list', 'country_id' => 'idV', 'placeholder' => true]),
                                            'data-selected' => $model->org_id,
                                            'data-child-selectors' => [
                                                '#' . Html::getInputId($model, 'client_id'),
                                            ],
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-md-4" id="client-id-wrapper">
                                    <?= $form->field($model, 'client_id')->widget(Select2::class, [
                                        'data' => Client::getListData('id', 'name', true, ['country_id' => $model->country_id]),
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'class' => 'form-control parent-depdropdown',
                                            'placeholder' => '[select one]',
                                            'data-url' => Url::to(['/core/client/get-list', 'org_id' => 'idV', 'country_id' => $model->country_id, 'placeholder' => true]),
                                            'data-selected' => $model->client_id,
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-md-4" id="region-id-wrapper">
                                    <?= $form->field($model, 'region_id')->widget(Select2::class, [
                                        'data' => CountryUnits::getListData('id', 'name', true, ['country_id' => $model->country_id, 'level' => CountryUnits::LEVEL_REGION]),
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'class' => 'form-control parent-depdropdown',
                                            'placeholder' => '[select one]',
                                            'data-url' => Url::to(['/core/country-units/get-list', 'country_id' => 'idV', 'level' => CountryUnits::LEVEL_REGION, 'placeholder' => true]),
                                            'data-selected' => $model->region_id,
                                            'data-child-selectors' => [
                                                '#' . Html::getInputId($model, 'district_id'),
                                            ],
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-md-4" id="district-id-wrapper">
                                    <?= $form->field($model, 'district_id')->widget(Select2::class, [
                                        'data' => CountryUnits::getListData('id', 'name', true, ['country_id' => $model->country_id, 'level' => CountryUnits::LEVEL_DISTRICT, 'parent_id' => $model->region_id]),
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'class' => 'form-control parent-depdropdown',
                                            'placeholder' => '[select one]',
                                            'data-url' => Url::to(['/core/country-units/get-list', 'country_id' => $model->country_id, 'placeholder' => true, 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_DISTRICT]),
                                            'data-selected' => $model->district_id,
                                            'data-child-selectors' => [
                                                '#' . Html::getInputId($model, 'ward_id'),
                                            ],
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-md-4" id="ward-id-wrapper">
                                    <?= $form->field($model, 'ward_id')->widget(Select2::class, [
                                        'data' => CountryUnits::getListData('id', 'name', true, ['country_id' => $model->country_id, 'level' => CountryUnits::LEVEL_WARD, 'parent_id' => $model->district_id]),
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'class' => 'form-control parent-depdropdown',
                                            'placeholder' => '[select one]',
                                            'data-url' => Url::to(['/core/country-units/get-list', 'country_id' => $model->country_id, 'placeholder' => true, 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_WARD]),
                                            'data-selected' => $model->ward_id,
                                            'data-child-selectors' => [
                                                '#' . Html::getInputId($model, 'village_id'),
                                            ],
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-md-4" id="village-id-wrapper">
                                    <?= $form->field($model, 'village_id')->widget(Select2::class, [
                                        'data' => CountryUnits::getListData('id', 'name', true, ['country_id' => $model->country_id, 'level' => CountryUnits::LEVEL_VILLAGE, 'parent_id' => $model->ward_id]),
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'placeholder' => '[select one]',
                                            'data-url' => Url::to(['/core/country-units/get-list', 'country_id' => $model->country_id, 'placeholder' => true, 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_VILLAGE]),
                                            'data-selected' => $model->village_id,
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false
                                        ],
                                    ]) ?>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-4">
                                <?= $form->field($model, 'role_id')->widget(Select2::class, [
                                    'data' => \backend\modules\auth\models\Roles::getListData('id', 'name', true, Session::isCountry() ? ['level_id' => UserLevels::LEVEL_COUNTRY] : []),
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'data-url' => Url::to(['role/get-list', 'level_id' => 'idV', 'placeholder' => true]),
                                        'data-selected' => $model->role_id,
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => false
                                    ],
                                ]) ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-4">
                            <?= $form->field($model, 'name') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'email') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'phone') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'timezone')->widget(Select2::class, [
                                'data' => \backend\modules\conf\models\Timezone::getListData(),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]) ?>
                        </div>
                        <?php foreach ($model->getAdditionalAttributes() as $attribute): ?>
                            <div class="col-md-4">
                                <?= $model->renderAdditionalAttribute($form, $attribute) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="kt-section">
                <div class="kt-section__body">
                    <h3 class="kt-section__title kt-section__title-lg"><?= Lang::t('Login details') ?></h3>
                </div>
                <div class="row">
                    <?php if ($model->isNewRecord): ?>
                        <div class="col-md-4">
                            <?= $form->field($model, 'auto_generate_password')->checkbox() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'require_password_change')->checkbox() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'send_email')->checkbox() ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-4">
                        <?= $form->field($model, 'username') ?>
                    </div>
                    <?php if ($model->isNewRecord): ?>
                        <div class="col-md-4 password-fields-wrapper">
                            <?= $form->field($model, 'password', [
                                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                            ])->widget(PasswordInput::class, [
                                'pluginOptions' => [
                                    'showMeter' => true,
                                    'toggleMask' => true,
                                ],
                                'options' => ['class' => 'form-control']
                            ]) ?>

                        </div>
                        <div class="col-md-4 password-fields-wrapper">
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
            <div class="kt-section kt-section--last">
                <div class="kt-section__body">
                    <h3 class="kt-section__title kt-section__title-lg"><?= Lang::t('Profile Image') ?></h3>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $this->render('_imageField', ['model' => $model]) ?>
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
    'orgIdFieldSelector' => '#' . Html::getInputId($model, 'country_id'),
];
$this->registerJs("MyApp.modules.auth.initUserForm(" . Json::encode($options) . ");");
?>