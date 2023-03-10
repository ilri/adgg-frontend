<?php

use backend\modules\auth\Session;
use common\helpers\Lang;
use common\widgets\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\modules\auth\models\Roles */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = $controller->getPageTitle();

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
        <div class="hidden" id="my-modal-notif"></div>
        <?= $form->field($model, 'name'); ?>
        <?php if (Session::isDev()|| Session::isSuperAdmin()): ?>
            <?= $form->field($model, 'level_id')->widget(Select2::class, [
                'data' => \backend\modules\auth\models\UserLevels::getListData(),
                'modal' => true,
                'theme' => Select2::THEME_BOOTSTRAP,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]) ?>
        <?php endif; ?>
        <?= $form->field($model, 'is_field_agent')->checkbox(); ?>
        <?php if (!$model->isNewRecord): ?>
            <?= $form->field($model, 'is_active')->checkbox(); ?>
        <?php endif; ?>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?>
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
<?php ActiveForm::end(); ?>