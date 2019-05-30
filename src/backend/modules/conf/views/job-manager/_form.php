<?php


use backend\modules\conf\models\Jobs;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form  ActiveForm */
/* @var $model Jobs */

$this->title = $this->context->pageTitle;

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

        <?= $form->field($model, 'id', []); ?>

        <?= $form->field($model, 'execution_type', [])->dropDownList(Jobs::executionTypeOptions()); ?>

        <?= $form->field($model, 'max_threads', []); ?>

        <?= $form->field($model, 'sleep', []); ?>

        <?= $form->field($model, 'start_time', [])->textInput(['class' => 'form-control show-timepicker']); ?>

        <?= $form->field($model, 'end_time', [])->textInput(['class' => 'form-control show-timepicker']); ?>

        <?= $form->field($model, 'is_active', [])->checkbox(); ?>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?>
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
<?php ActiveForm::end(); ?>