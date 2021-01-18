x<?php

use backend\controllers\BackendController;
use backend\modules\core\models\Client;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Organization;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form ActiveForm */
/* @var $controller BackendController */
/* @var $model Client */
$controller = Yii::$app->controller;
$this->title = $controller->getPageTitle();
?>
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title"><?= Html::encode($this->title) ?></h3>
        </div>
    </div>
    <?php
    $form = ActiveForm::begin([
        'id' => 'my-modal-form',
        'layout' => 'horizontal',
        'options' => ['class' => 'kt-form kt-form--label-right'],
        'fieldClass' => \common\forms\ActiveField::class,
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-md-3 col-form-label',
                'offset' => 'offset-md-3',
            'wrapper' => 'col-md-6',
            'error' => '',
            'hint' => '',
        ],
    ],
]);
?>
    <?= $form->field($model, 'name', []) ?>
    <?= $form->field($model, 'description', [])->textarea() ?>

    <?= $form->field($model, 'country_id')->widget(Select2::class, [
        'data' => Country::getListData(),
        'modal' => false,
        'value' => $model->country->id,
        //'disabled'=>true,
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'class' => 'form-control parent-depdropdown',
            'data-child-selectors' => [
                '#' . Html::getInputId($model, 'org_id'),
            ],
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>
    <?= $form->field($model, 'org_id')->widget(Select2::class, [
        'data' => Organization::getListData('id', 'name'),
        'options' => [
            'class' => 'form-control parent-depdropdown',
            'placeholder' => '[select one]',
            'data-url' => Url::to(['organization/get-list', 'country_id' => 'idV', 'placeholder' => true]),
            'data-selected' => $model->org_id,
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>
    <?php if (!$model->isNewRecord): ?>
        <?= $form->field($model, 'is_active', [])->checkbox() ?>
    <?php endif; ?>
    <?php foreach ($model->getAdditionalAttributes() as $attribute): ?>
        <?= $form->field($model, $attribute, []) ?>
    <?php endforeach; ?>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary">
        <?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?>
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<?php ActiveForm::end(); ?>
