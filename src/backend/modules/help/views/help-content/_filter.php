<?php

use backend\modules\help\models\HelpModules;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use yii\bootstrap4\Html;

/* @var $model \backend\modules\help\models\HelpContent */
?>

<div class="accordion mb-5" id="accordion">
    <div class="card">
        <div class="card-header">
            <div class="card-title" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                 aria-controls="collapseOne">
                <i class="fas fa-chevron-down"></i> <?= Lang::t('Filters') ?>:
            </div>
        </div>
        <div id="collapseOne" class="collapse show" data-parent="#accordion">
            <div class="card-body">
                <?= Html::beginForm(['read'], 'get', ['class' => '', 'id' => 'help-filter-form', ]) ?>
                <div class="form-row align-items-center">
                    <div class="col-lg-2">
                        <?= Html::label('Module') ?>
                        <?= Select2::widget([
                            'name' => 'module',
                            'value' => $filterOptions['module'],
                            'data' => HelpModules::getListData(),
                            'options' => [
                                'placeholder' => "--All Modules--",
                                'class' => 'form-control select2',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>

                    <div class="col-lg-2">
                        <?= Html::label('Name') ?>
                        <?= Html::textInput('name', $filterOptions['name'], ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-2">
                        <br>
                        <button class="btn btn-primary pull-left" type="submit"><?= Lang::t('Go') ?></button>
                        &nbsp;
                        <button class="btn btn-default" type="reset"
                                onclick="$('select.select2').val('').trigger('change');"><?= Lang::t('Reset') ?></button>
                    </div>
                    <div class="col-lg-2 offset-8">
                        <?= Html::label('') ?>
                        <a target="_blank" class="btn btn-brand btn-bold pull-right"
                           href="<?= Url::to(['read', 'format' => 'pdf', 'module' => $filterOptions['module'], 'name' => $filterOptions['name']]) ?>"
                        ><i class="far fa-file-pdf"></i> Read as PDF</a>
                    </div>
                    <div class="col-lg-2">
                        <?= Html::label('') ?>
                        <a target="_blank" class="btn btn-brand btn-bold pull-right"
                           href="<?= Url::to(['read', 'format' => 'word', 'module' => $filterOptions['module'], 'name' => $filterOptions['name']]) ?>"
                        ><i class="far fa-file-word"></i> Read as Word</a>
                    </div>
                </div>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>