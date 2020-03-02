<?php

use backend\modules\auth\Session;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\Country;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use backend\modules\auth\models\Users;
use backend\modules\auth\models\AuditTrail;
use yii\bootstrap4\Html;

/* @var $model AuditTrail */
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
                <?= Html::beginForm(['index'], 'get', ['class' => '', 'id' => 'grid-filter-form', 'data-grid' => $model->getPjaxWidgetId()]) ?>
                <div class="form-row align-items-center">
                    <?php if (!Session::isCountry()): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('country_id')) ?>
                            <?= Select2::widget([
                                'name' => 'country_id',
                                'value' => $model->country_id,
                                'data' => Country::getListData('id', 'name', false),
                                'options' => [
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'placeholder' => '[select one]',
                                    'data-child-selectors' => [
                                        '#' . Html::getInputId($model, 'user_id'),
                                    ],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('user_id')) ?>
                        <?= Select2::widget([
                            'name' => 'user_id',
                            'value' => $model->user_id,
                            'data' => Users::getListData('id', 'name', false),
                            'options' => [
                                'id' => Html::getInputId($model, 'user_id'),
                                'placeholder' => '[Select One]',
                                'data-url' => Url::to(['user/get-list', 'country_id' => 'idV', 'placeholder' => true]),
                                'data-selected' => $model->user_id,
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('action')) ?>
                        <?= Select2::widget([
                            'name' => 'action',
                            'value' => $model->action,
                            'data' => AuditTrail::actionOptions(false),
                            'options' => [
                                'placeholder' => '[all]',
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label('From') ?>
                        <?= Html::textInput('from', $model->_dateFilterFrom, ['class' => 'form-control show-datepicker', 'placeholder' => 'From']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label('To') ?>
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

