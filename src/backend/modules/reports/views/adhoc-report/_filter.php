<?php

use backend\modules\reports\models\AdhocReport;
use common\helpers\Lang;
use common\widgets\select2\Select2;
use yii\bootstrap4\Html;

/* @var $model AdhocReport */
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
                    <div class="col-lg-2">
                        <?= Html::label($model->getAttributeLabel('status')) ?>
                        <?= Select2::widget([
                            'name' => 'status',
                            'value' => $model->status,
                            'data' => AdhocReport::statusOptions(false),
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