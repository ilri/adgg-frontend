<?php

use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\modules\auth\models\Resources */
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
        <?= $form->field($model, 'id'); ?>
        <?= $form->field($model, 'name'); ?>
        <?= $form->field($model, 'viewable')->checkbox(); ?>
        <?= $form->field($model, 'creatable')->checkbox(); ?>
        <?= $form->field($model, 'editable')->checkbox(); ?>
        <?= $form->field($model, 'deletable')->checkbox(); ?>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?>
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
<?php ActiveForm::end(); ?>