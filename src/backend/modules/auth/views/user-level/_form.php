<?php

use backend\modules\auth\models\Resources;
use backend\modules\auth\models\UserLevels;
use common\helpers\Lang;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model Resources */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = $controller->getPageTitle();
?>
<?php
$form = ActiveForm::begin([
    'id' => 'my-modal-form',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'fieldClass' => \common\forms\ActiveField::class,
    'options' => ['class' => 'kt-form kt-form--label-right'],
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
    <div class="modal-header">
        <h5 class="modal-title"><?= Html::encode($this->title); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="alert hidden" id="my-modal-notif"></div>
        <?= $form->field($model, 'id'); ?>
        <?= $form->field($model, 'name'); ?>
        <?= $form->field($model, 'forbidden_items')->widget(Select2::class, [
            'data' => Resources::getListData('id', 'name'),
            'modal' => true,
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => ['multiple' => true],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]) ?>
        <?= $form->field($model, 'parent_id')->widget(Select2::class, [
            'data' => UserLevels::getListData('id', 'name', true),
            'modal' => true,
            'theme' => Select2::THEME_BOOTSTRAP,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]) ?>
        <?= $form->field($model, 'is_active')->checkbox(); ?>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?>
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
<?php ActiveForm::end(); ?>