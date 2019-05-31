<?php

use backend\modules\auth\models\UserLevels;
use backend\modules\core\models\Organization;
use common\helpers\Lang;
use common\widgets\select2\Select2;
use backend\modules\auth\models\Users;
use yii\bootstrap4\Html;

/* @var $model Organization */
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
                <?= Html::hiddenInput('is_member', $model->is_member) ?>
                <?= Html::hiddenInput('business_type', $model->business_type) ?>
                <?= Html::hiddenInput('tab', Yii::$app->request->get('tab', null)) ?>
                <div class="form-row align-items-center">
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('status')) ?>
                        <?= Select2::widget([
                            'name' => 'status',
                            'value' => $model->status,
                            'data' => Organization::statusOptions(),
                            'options' => [
                                'placeholder' => "",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('account_no')) ?>
                        <?= Html::textInput('account_no', $model->account_no, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('name')) ?>
                        <?= Html::textInput('name', $model->name, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('account_manager_id')) ?>
                        <?= Select2::widget([
                            'name' => 'account_manager_id',
                            'value' => $model->account_manager_id,
                            'data' => Users::getListData('id', 'name', false, '[[level_id]]<>:level_id', [':level_id' => UserLevels::LEVEL_COUNTRY]),
                            'options' => [
                                'placeholder' => "",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                    <?php if ($model->is_member): ?>
                        <div class="col-lg-2">
                            <?= Html::label($model->getAttributeLabel('is_credit_requested')) ?>
                            <?= Select2::widget([
                                'name' => 'is_credit_requested',
                                'value' => $model->is_credit_requested,
                                'data' => \common\helpers\Utils::booleanOptions(),
                                'options' => [
                                    'placeholder' => "",
                                    'class' => 'form-control select2',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    <?php endif; ?>
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