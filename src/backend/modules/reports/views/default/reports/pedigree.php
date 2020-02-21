<?php

/* @var $this yii\web\View */

use backend\modules\core\Constants;
use backend\modules\core\models\CountryUnits;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use yii\bootstrap\Html;

/* @var $country_id int */
/* @var $filterOptions array */

$this->title = 'Pedigree';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->user->canView(Constants::RES_REPORT_BUILDER)): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <h3><?= Lang::t(strtoupper($this->title)) ?></h3>
                <hr>
                <div class="card card-body">
                    <?= Html::beginForm('generate', 'post', ['class' => '', 'id' => 'grid-filter-form']) ?>
                    <div class="form-row align-items-center">
                        <div class="col-lg-2">
                            <?= Html::label('Region ID') ?>
                            <?= Select2::widget([
                                'name' => 'region_id',
                                'value' => $filterOptions['region_id'],
                                'data' => CountryUnits::getListData('id', 'name', false, ['country_id' => $country_id, 'level' => CountryUnits::LEVEL_REGION]),
                                'options' => [
                                    'id' => 'region_id',
                                    'placeholder' => "",
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['/core/country-units/get-list', 'country_id' => 'idV', 'level' => CountryUnits::LEVEL_REGION, 'placeholder' => true]),
                                    'data-selected' => $filterOptions['region_id'],
                                    'data-child-selectors' => [
                                        '#' . 'district_id',
                                    ],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                        <div class="col-lg-2">
                            <?= Html::label('District ID') ?>
                            <?= Select2::widget([
                                'name' => 'district_id',
                                'value' => $filterOptions['district_id'],
                                'data' => CountryUnits::getListData('id', 'name', false, ['parent_id' => $filterOptions['region_id'], 'level' => CountryUnits::LEVEL_DISTRICT]),
                                'options' => [
                                    'id' => 'district_id',
                                    'placeholder' => "-Select-",
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['/core/country-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_DISTRICT, 'placeholder' => true]),
                                    'data-selected' => $filterOptions['district_id'],
                                    'data-child-selectors' => [
                                        '#' . 'ward_id',
                                    ],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                        <div class="col-lg-2">
                            <?= Html::label('Ward ID') ?>
                            <?= Select2::widget([
                                'name' => 'ward_id',
                                'value' => $filterOptions['ward_id'],
                                'data' => CountryUnits::getListData('id', 'name', false, ['parent_id' => $filterOptions['district_id'], 'level' => CountryUnits::LEVEL_WARD]),
                                'options' => [
                                    'id' => 'ward_id',
                                    'placeholder' => "",
                                    'class' => 'form-control select2 parent-depdropdown',
                                    'data-url' => Url::to(['/core/country-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_WARD, 'placeholder' => true]),
                                    'data-selected' => $filterOptions['ward_id'],
                                    'data-child-selectors' => [
                                        '#' . 'village_id',
                                    ],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                        <div class="col-lg-2">
                            <?= Html::label('Village ID') ?>
                            <?= Select2::widget([
                                'name' => 'village_id',
                                'value' => $filterOptions['village_id'],
                                'data' => CountryUnits::getListData('id', 'name', false, ['parent_id' => $filterOptions['ward_id'], 'level' => CountryUnits::LEVEL_VILLAGE]),
                                'options' => [
                                    'id' => 'village_id',
                                    'placeholder' => "",
                                    'class' => 'form-control select2',
                                    'data-url' => Url::to(['/core/country-units/get-list', 'parent_id' => 'idV', 'level' => CountryUnits::LEVEL_VILLAGE, 'placeholder' => true]),
                                    'data-selected' => $filterOptions['village_id'],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                        <div class="col-lg-2">
                            <?= Html::label('From') ?>
                            <?= Html::textInput('from', $filterOptions['dateFilterFrom'], ['class' => 'form-control show-datepicker', 'placeholder' => 'From']) ?>
                        </div>
                        <div class="col-lg-2">
                            <?= Html::label('To') ?>
                            <?= Html::textInput('to', $filterOptions['dateFilterTo'], ['class' => 'form-control show-datepicker', 'placeholder' => 'To']) ?>
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
<?php endif; ?>