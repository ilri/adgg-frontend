<?php

use backend\modules\auth\models\Users;
use common\helpers\Lang;
use common\widgets\select2\Select2;
use yii\bootstrap4\Html;

/* @var $model Users */
$url = ['index'];
?>
<div class="accordion mb-5" id="accordion">
    <div class="card">
        <div class="card-header">
            <div class="card-title" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                 aria-controls="collapseOne">
                <i class="fas fa-chevron-right"></i> <?= Lang::t('Filters') ?>:
            </div>
        </div>
        <div id="collapseOne" class="collapse" data-parent="#accordion">
            <div class="card-body">
                <?= Html::beginForm($url, 'get', ['class' => '', 'id' => 'grid-filter-form', 'data-grid' => $model->getPjaxWidgetId()]) ?>
                <div class="form-row align-items-center">
                    <?php if (!\backend\modules\auth\Session::isOrganizationRef()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('country_id')) ?>
                            <?= Select2::widget([
                                'name' => 'country_id',
                                'value' => $model->country_id,
                                'data' => \backend\modules\core\models\OrganizationRef::getListData('id', 'name', false),
                                'options' => [
                                    'placeholder' => '[all]',
                                    'class' => 'form-control mb-2 select2'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('level_id')) ?>
                            <?= Select2::widget([
                                'name' => 'level_id',
                                'value' => $model->level_id,
                                'data' => Users::levelIdListData(false),
                                'options' => [
                                    'placeholder' => '[all]',
                                    'class' => 'form-control mb-2 select2'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('role_id')) ?>
                        <?= Select2::widget([
                            'name' => 'role_id',
                            'value' => $model->role_id,
                            'data' => \backend\modules\auth\models\Roles::getListData('id', 'name', false, \backend\modules\auth\Session::isOrganizationRef() ? ['level_id' => \backend\modules\auth\models\UserLevels::LEVEL_COUNTRY] : []),
                            'options' => [
                                'placeholder' => '[all]',
                                'class' => 'form-control mb-2 select2'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('status')) ?>
                        <?= Select2::widget([
                            'name' => 'status',
                            'value' => $model->status,
                            'data' => Users::statusOptions(false),
                            'options' => [
                                'placeholder' => '[all]',
                                'class' => 'form-control mb-2 select2'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('name')) ?>
                        <?= Html::textInput('name', $model->name, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('username')) ?>
                        <?= Html::textInput('username', $model->username, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('email')) ?>
                        <?= Html::textInput('email', $model->email, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('phone')) ?>
                        <?= Html::textInput('phone', $model->phone, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('last_login')) ?>
                        <?= Html::textInput('from', $model->_dateFilterFrom, ['class' => 'form-control show-datepicker', 'placeholder' => 'From']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('last_login')) ?>
                        <?= Html::textInput('to', $model->_dateFilterTo, ['class' => 'form-control show-datepicker', 'placeholder' => 'To']) ?>
                    </div>
                    <div class="col-lg-2">
                        <br>
                        <button class="btn btn-primary pull-left" type="submit"><?= Lang::t('Go') ?></button>
                        &nbsp;
                        <button class="btn btn-default" type="reset"
                                onclick="$('select.select2').val('').trigger('change');"><?= Lang::t('Reset') ?></button>
                    </div>
                </div>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>